<?php 
namespace App;

    class Auth {

        private $pdo;

        private $loginPath;

        public function __construct(\PDO $pdo, string $loginPath) 
        {
            $this->pdo = $pdo;
            $this->loginPath = $loginPath;
        }

        public function user(): ?User
        {
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $id = $_SESSION['auth'] ?? null;
            if($id === null) {
                return null;
            }
            $statement = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
            $statement->execute([$id]);
            $user = $statement->fetchObject(User::class);
            return $user ?: null;


        }

        public function login(string $username, string $password): ?User
        {
            $statement = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
            $statement->execute([
                'username' => $username,
            ]);
            $statement->setFetchMode(\PDO::FETCH_CLASS, User::class);
            $user = $statement->fetch();
            if($user == false) 
            {
                return null;
            }
            if (password_verify($password, $user->password)) {
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['auth'] = $user->id;
                return $user; 
            }
            return null;

        }

        public function requireRole(String ...$roles): void {

            $user = $this->user();

            if($user == null || (!in_array($user->role, $roles))) {
                header("Location:" . $this->loginPath . "?forbid=1");
                exit();
            }
        }



    }
?>