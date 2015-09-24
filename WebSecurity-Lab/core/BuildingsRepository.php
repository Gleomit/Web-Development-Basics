<?php

namespace Core;

class BuildingsRepository
{
    /**
     * @var Database
     */
    private $db;

    /**
     * @var User
     */
    private $user;

    public function __construct(Database $db, User $user) {
        $this->db = $db;
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function getBuildings() {
        $result = $this->db->prepare("
            SELECT  b.id, b.name, bl.level,
              (SELECT gold FROM building_levels WHERE building_id = ub.building_id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS gold,
              (SELECT food FROM building_levels WHERE building_id = ub.building_id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS food
            FROM user_buildings as ub
            INNER JOIN buildings AS b ON b.id = ub.building_id
            INNER JOIN building_levels AS bl ON bl.id = ub.level_id
            WHERE ub.user_id = ?;
        ");

        $result->execute([$this->user->getId()]);

        $data = $result->fetchAll();

        return $data;
    }

    public function evolve($buildingId) {
        //check building
        $result = $this->db->prepare("SELECT id FROM buildings WHERE id = ?");
        $result->execute([$buildingId]);

        if($result->rowCount() < 0) {
            throw new \Exception("Building with such id does not exists");
        }

        //get resources
        $resources = $this->db->prepare("
            SELECT
              (SELECT gold FROM building_levels WHERE building_id = b.id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS gold,
              (SELECT food FROM building_levels WHERE building_id = b.id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS food
            FROM buildings as b
            INNER JOIN user_buildings AS ub ON ub.building_id = b.id
            INNER JOIN building_levels AS bl ON bl.id = ub.level_id
            WHERE ub.user_id = ? AND b.id = ?;
        ");

        $resources->execute([
            $this->user->getId(),
            $buildingId
        ]);

        $resourcesData = $resources->fetch();

        if($this->getUser()->getFood() < $resourcesData['food'] || $this->getUser()->getGold() < $resourcesData['gold']) {
            throw new \Exception("No resources");
        }

        //max level
        $maxLevel = $this->db->prepare("
            SELECT
              MAX(bl.level) AS level
            FROM  building_levels bl
            WHERE bl.building_id = ?
        ");

        $maxLevel->execute([$buildingId]);

        $maxLevelData = $maxLevel->fetch();

        //current level
        $currentLevel = $this->db->prepare("
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

        $resourceUpdate = $this->db->prepare("
            UPDATE
              users
            SET
              gold = gold - ?, food = food - ?
            WHERE id = ?
        ");

        $resourceUpdate->execute([
            $resourcesData['gold'],
            $resourcesData['food'],
            $this->getUser()->getId()
        ]);

        if($resourceUpdate->rowCount() > 0) {
            $levelUpdate = $this->db->prepare("
                UPDATE
                  user_buildings ub
                SET
                  ub.level_id = (SELECT bl.id FROM building_levels bl WHERE level = ? AND bl.building_id = ub.building_id)
                WHERE ub.user_id = ? AND ub.building_id = ?
            ");

            $levelUpdate->execute([
                $currentLevelData['level'] + 1,
                $this->getUser()->getId(),
                $buildingId
            ]);

            if($levelUpdate->rowCount() > 0) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                throw new \Exception("Level up error");
            }
        } else {
            throw new \Exception("Resource update error");
        }
    }
}