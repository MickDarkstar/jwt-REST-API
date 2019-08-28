<?php

/**
 *
 * @version 2.0
 * @author Micke@tempory.org
 */
class UserRepository extends BaseRepository
{
    const DB_TABLE = "users";

    private $db;

    public function __construct(PDO $pdo = null)
    {
        parent::__construct();

        if (isset($pdo))
            $this->db = $pdo;
        else
            $this->db = parent::$dbHandle;
    }

    public function all()
    {
        $list = [];
        $req = $this->db->query("SELECT * FROM `" . self::DB_TABLE . "` ORDER BY created");
        $req->execute();
        foreach ($req->fetchAll() as $result) {
            $list[] = self::mapToObject($result);
        }
        return $list;
    }

    /**
     * @param $username
     * @param $userpassword
     * @return AppUser|null
     */
    public function getByUsernameAndUserpassword($username, $userpassword)
    {
        $req = $this->db->prepare("SELECT * FROM `" . self::DB_TABLE . "` WHERE name = :username AND password = :userpassword");
        $req->bindParam(':username', $username, PDO::PARAM_STR);
        $req->bindParam(':userpassword', $userpassword, PDO::PARAM_STR);
        $req->execute();
        $result = $req->fetch();

        return self::mapToObject($result);
    }

    /**
     * Returns true if username is in use
     * @param string $useremail
     * @return boolean
     */
    public function usernameExists($username)
    {
        $req = $this->db->prepare("SELECT * FROM `" . self::DB_TABLE . "` WHERE name = :username");
        $req->bindParam(':username', $username, PDO::PARAM_STR);
        $req->execute();
        $result = $req->fetch();
        return ($result > 0);
    }

    /**
     * Returns true if useremail is in use
     * @param string $useremail
     * @return boolean
     */
    public function useremailExists($useremail)
    {
        $req = $this->db->prepare("SELECT * FROM `" . self::DB_TABLE . "` WHERE email = :useremail");
        $req->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $req->execute();
        $result = $req->fetch();
        return ($result > 0);
    }

    public function find($id)
    {
        $id = intval($id);
        $req = $this->db->prepare("SELECT * FROM `" . self::DB_TABLE . "` WHERE id = :id");
        $req->execute(array('id' => $id));
        $result = $req->fetch();

        return self::mapToObject($result);
    }

    public function create(AppUser $user)
    {
        $req = $this->db->prepare("INSERT INTO `" . self::DB_TABLE . "`(name, password, email, created_date)VALUES(:name, :password, :email, :created_date)");
        $req->bindParam(':name', $user->name, PDO::PARAM_STR);
        $req->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $req->bindParam(':email', $user->email, PDO::PARAM_STR);
        $req->bindParam(':created_date', date("Y-m-d"), PDO::PARAM_STR);

        if ($req->execute() == false) {
            return false;
        }

        return self::find($this->db->lastInsertId());
    }

    public function update($id, $name, $email, $password)
    {
        $req = $this->db->prepare("UPDATE `" . self::DB_TABLE . "` SET name=:name, password=:password, email=:email WHERE :id=id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->bindParam(':name', $name, PDO::PARAM_STR);
        $req->bindParam(':email', $name, PDO::PARAM_STR);
        $req->bindParam(':password', $password, PDO::PARAM_STR);
        $req->execute();
    }

    private function mapToObject($result)
    {
        return new AppUser(
            $result['id'],
            $result['firstname'],
            $result['lastname'],
            $result['email'],
            $result['password']
        );
    }

    private function mapToObjects($rows)
    {
        if ($rows == null) {
            return null;
        }
        $result = [];
        foreach ($rows as $row) {
            $object = self::mapToObject($row);
            array_push($result, $object);
        }
        return $result;
    }
}
