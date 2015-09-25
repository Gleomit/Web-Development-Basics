<?php

namespace SoftUni\Models;

use SoftUni\Config\DatabaseConfig;
use SoftUni\Core\Database;
use SoftUni\Helpers\Session;
use SoftUni\ViewModels\UserInformation;

class Building
{
    public function evolve($buildingId) {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        //check building
        $result = $db->prepare("SELECT id FROM buildings WHERE id = ?");
        $result->execute([$buildingId]);

        if($result->rowCount() < 0) {
            throw new \Exception("Building with such id does not exists");
        }

        //get resources
        $resources = $db->prepare("
            SELECT
              (SELECT gold FROM building_levels WHERE building_id = b.id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS gold,
              (SELECT food FROM building_levels WHERE building_id = b.id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS food
            FROM buildings as b
            INNER JOIN user_buildings AS ub ON ub.building_id = b.id
            INNER JOIN building_levels AS bl ON bl.id = ub.level_id
            WHERE ub.user_id = ? AND b.id = ?;
        ");

        $userModel = new User();
        $userInfo = $userModel->getInfo(Session::get('id'));

        $userInfo = new UserInformation(
            $userInfo['username'],
            $userInfo['id'],
            $userInfo['gold'],
            $userInfo['food']
        );

        $resources->execute([
            $userInfo->getId(),
            $buildingId
        ]);

        $resourcesData = $resources->fetch();

        if($userInfo->getFood() < $resourcesData['food'] || $userInfo->getGold() < $resourcesData['gold']) {
            throw new \Exception("No resources");
        }

        //max level
        $maxLevel = $db->prepare("
            SELECT
              MAX(bl.level) AS level
            FROM  building_levels bl
            WHERE bl.building_id = ?
        ");

        $maxLevel->execute([$buildingId]);

        $maxLevelData = $maxLevel->fetch();

        //current level
        $currentLevel = $db->prepare("
            SELECT
                bl.level
            FROM user_buildings ub
                JOIN building_levels bl ON bl.id = ub.level_id
            WHERE ub.building_id = ?
        ");

        $currentLevel->execute([$buildingId]);

        $currentLevelData = $currentLevel->fetch();

        if($maxLevelData['level'] < $currentLevelData['level']) {
            throw new \Exception("Max level reached");
        }

        $this->db->beginTransaction();

        $resourceUpdate = $db->prepare("
            UPDATE
              users
            SET
              gold = gold - ?, food = food - ?
            WHERE id = ?
        ");

        $resourceUpdate->execute([
            $resourcesData['gold'],
            $resourcesData['food'],
            $userInfo->getId()
        ]);

        if($resourceUpdate->rowCount() > 0) {
            $levelUpdate = $db->prepare("
                UPDATE
                  user_buildings ub
                SET
                  ub.level_id = (SELECT bl.id FROM building_levels bl WHERE level = ? AND bl.building_id = ub.building_id)
                WHERE ub.user_id = ? AND ub.building_id = ?
            ");

            $levelUpdate->execute([
                $currentLevelData['level'] + 1,
                $userInfo->getId(),
                $buildingId
            ]);

            if($levelUpdate->rowCount() > 0) {
                $db->commit();
                return true;
            } else {
                $db->rollBack();
                throw new \Exception("Level up error");
            }
        } else {
            throw new \Exception("Resource update error");
        }
    }
}