<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase {
    /**
     * @var Auth
     */
    private $auth;

    private $session = [];

    /**
     * @before
     */
    public function setAuth() {

        $pdo = new PDO("sqlite::memory:", null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]); // les tests sont lancés en mémoire
    
        $pdo->query('CREATE TABLE users (id INTEGER,username TEXT, password TEXT, role TEXT)');
    
        for($i = 1; $i <= 10; $i++) {
            $hash = password_hash("user$i", PASSWORD_BCRYPT, ['cost' => 4]);
            $pdo->query("INSERT INTO users (id, username, password, role) VALUES ('$i', 'user$i', '$hash', 'user$i')");
        }

        $this->auth = new App\Auth($pdo, "/login", $this->session);

    }

    public function testLoginWithBadUsername() {
        $this->assertNull($this->auth->login('toto','toto'));
    }

    public function testLoginWithBadPassword() {
        $this->assertNull($this->auth->login('user1','toto'));
    }

    public function testLoginSuccess() {
        $this->assertObjectHasAttribute('username', $this->auth->login('user1','user1'));
        // Ici comme la variable session est passée par référence, la session de cette classe est la même que Auth
        $this->assertEquals($this->session['auth'],1);
    }

    public function testUserWhenNotConnected() {
        $this->assertNull($this->auth->user());
    }

    public function testUserWhenConnectedWithUnexistingUser() {
        $this->session['auth'] = 11;
        $this->assertNull($this->auth->user());
    }

    public function testUserWhenConnected() {
        $this->session['auth'] = 5;
        $user = $this->auth->user();
        $this->assertIsObject($user);
        $this->assertEquals(5, $user->id);
    }

    public function testRequireRole() {
        $this->expectNotToPerformAssertions();    
        $this->session['auth'] = 2;
        $this->auth->requireRole('user2');
    }

    public function testRequireRoleWithoutLoginThrowException() {
        $this->expectException(App\Exception\ForbiddenException::class);    
        $this->auth->requireRole('user3');
    }

    public function testRequireRoleThrowException() {
        $this->session['auth'] = 2;
        $this->expectException(App\Exception\ForbiddenException::class);    
        $this->auth->requireRole('user3');
    }



    

}