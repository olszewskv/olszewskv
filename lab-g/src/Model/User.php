<?php
namespace App\Model;

use App\Service\Config;

class User
{
    private ?int $id = null;
    private ?string $nickname = null;
    private ?string $email = null;
    private ?string $age = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): User
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): User
    {
        $this->age = $age;

        return $this;
    }

    public static function fromArray($array): User
    {
        $user = new self();
        $user->fill($array);

        return $user;
    }

    public function fill($array): User
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['nickname'])) {
            $this->setNickname($array['nickname']);
        }
        if (isset($array['email'])) {
            $this->setEmail($array['email']);
        }
        if (isset($array['age'])) {
            $this->setAge($array['age']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM user';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $users = [];
        $usersArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($usersArray as $userArray) {
            $users[] = self::fromArray($userArray);
        }

        return $users;
    }

    public static function find($id): ?User
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM user WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $userArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $userArray) {
            return null;
        }
        $user = User::fromArray($userArray);

        return $user;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO user (nickname, email, age) VALUES (:nickname, :email, :age)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'nickname' => $this->getNickname(),
                'email' => $this->getEmail(),
                'age' => $this->getAge(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE user SET nickname = :nickname, email = :email, age = :age WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':nickname' => $this->getNickname(),
                ':email' => $this->getEmail(),
                ':age' => $this->getAge(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM user WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setNickname(null);
        $this->setEmail(null);
        $this->setAge(null);
    }
}
