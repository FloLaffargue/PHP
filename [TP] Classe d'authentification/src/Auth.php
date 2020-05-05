<?php 
namespace App;

    class Auth {

        private $pdo;

        private $loginPath;

        private $session;

        public function __construct(\PDO $pdo, string $loginPath, array &$session) 
        {
            $this->pdo = $pdo;
            $this->loginPath = $loginPath;
            $this->session = &$session;
        }

        public function user(): ?User
        {
            // if(session_status() === PHP_SESSION_NONE) {
            //     session_start();
            // }
            // $id = $_SESSION['auth'] ?? null;
            $id = $this->session['auth'] ?? null;
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
                // if(session_status() === PHP_SESSION_NONE) {
                //     session_start();
                // }
                // $_SESSION['auth'] = $user->id;
                $this->session['auth'] = 1;
                return $user; 
            }
            return null;

        }

        public function requireRole(String ...$roles): void {

            $user = $this->user();

            if($user == null) {
                // header("Location:" . $this->loginPath . "?forbid=1");
                // exit();
                throw new \App\Exception\ForbiddenException("Vous devez être connecté");
            }

            if(!in_array($user->role, $roles)) {
                $roles = implode(',', $roles);
                throw new \App\Exception\ForbiddenException("Vous n'avez pas le rôle suffisant ($user->role) parmis les roles ($roles)");
            }
        }



    }
?>