<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once 'src/controllers/UserController.php';
require_once 'src/models/User.php';

class UserControllerTest extends TestCase {
    /**
     * @var UserController
     */
    private $userController;

    /**
     * @var UserRepository|MockObject
     */
    private $userRepository;

    protected function setUp(): void {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userController = new UserController();
        $this->userController->userRepository = $this->userRepository;
    }

    public function testRegister() {
        $_POST['email'] = 'newuser@example.com';
        $_POST['password'] = 'password';
        $_POST['first_name'] = 'New';
        $_POST['last_name'] = 'User';
        $_POST['username'] = 'newuser';
        $_POST['city'] = 'New City';

        $user = new User('newuser@example.com', password_hash('password', PASSWORD_BCRYPT), 'New', 'User', 'newuser', 'New City', null);
        $this->userRepository->expects($this->once())->method('save')->with($this->equalTo($user));
        $this->userRepository->expects($this->once())->method('findByEmail')->with('newuser@example.com')->willReturn($user);

        $this->userController->register();

        $this->assertEquals($user->getId(), $_SESSION['user']);
    }

    public function testLogin() {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password';

        $user = new User('test@example.com', password_hash('password', PASSWORD_BCRYPT), 'Test', 'User', 'testuser', 'Test City', null);
        $this->userRepository->expects($this->once())->method('findByEmail')->with('test@example.com')->willReturn($user);

        $this->userController->login();

        $this->assertEquals($user->getId(), $_SESSION['user']);
    }

    public function testUpdateProfilePicture() {
        $_SESSION['user'] = 1;

        $user = new User('test@example.com', password_hash('password', PASSWORD_BCRYPT), 'Test', 'User', 'testuser', 'Test City', null);
        $this->userRepository->expects($this->once())->method('find')->with(1)->willReturn($user);

        $_FILES['profile_picture'] = [
            'name' => 'avatar.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => 'public/images/avatar.jpg',
            'error' => UPLOAD_ERR_OK,
            'size' => filesize('public/images/avatar.jpg')
        ];

        $profilePicture = file_get_contents('public/images/avatar.jpg');
        $this->userRepository->expects($this->once())->method('updateProfilePicture')->with(1, $profilePicture);

        $this->userController->updateProfilePicture();

        $this->assertEquals($user->getId(), $_SESSION['user']);
    }

    public function testAddFriend() {
        $_SESSION['user'] = 1;
        $_POST['friend_email'] = 'friend@example.com';

        $user = new User('friend@example.com', password_hash('password', PASSWORD_BCRYPT), 'Friend', 'User', 'frienduser', 'Friend City', null);
        $this->userRepository->expects($this->once())->method('findByEmail')->with('friend@example.com')->willReturn($user);
        $this->userRepository->expects($this->once())->method('addFriend')->with(1, $user->getId());

        $this->userController->addFriend();

        $this->assertEquals(1, $_SESSION['user']);
    }
}
