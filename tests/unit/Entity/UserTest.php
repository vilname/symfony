<?php


namespace App\Tests\unit\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;
use DateTime;

class UserTest extends TestCase
{
    public function userDataProvider(): array
    {
        $expectedPositive = [
            'id' => 5,
            'login' => 'Terry Pratchett',
            'password' => 'The Colour of Magic',
            'roles' => ['ROLE_APPERTICE'],
            'createdAt' => DateTime::createFromFormat('U',(string)time())->format('Y-m-d h:i:s'),
        ];
        $positiveUser = $this->addLogin($this->makeUser($expectedPositive), $expectedPositive);
        $expectedNoAuthor = [
            'id' => 30,
            'login' => null,
            'password' => 'The Colour of Magic',
            'roles' => ['ROLE_APPERTICE'],
            'createdAt' => DateTime::createFromFormat('U',(string)time())->format('Y-m-d h:i:s'),
        ];
        $expectedNoCreatedAt = [
            'id' => 42,
            'login' => 'Douglas Adams',
            'password' => 'The Colour of Magic',
            'roles' => ['ROLE_APPERTICE'],
            'createdAt' => '',
        ];
        return [
            'positive' => [
                $positiveUser,
                $expectedPositive,
                0,
            ],
            'no login' => [
                $this->makeUser($expectedNoAuthor),
                $expectedNoAuthor,
                0
            ],
            'no createdAt' => [
                $this->addLogin($this->makeUser($expectedNoCreatedAt), $expectedNoCreatedAt),
                $expectedNoCreatedAt,
                null
            ],
            'positive with delay' => [
                $positiveUser,
                $expectedPositive,
                2
            ],
        ];
    }

    /**
     * @dataProvider userDataProvider
     * @group time-sensitive
     */
    public function testToFeedReturnsCorrectValues(User $user, array $expected, ?int $delay = null): void
    {
        ClockMock::register(User::class);
        $user = $this->setCreatedAtWithDelay($user, $delay);
        $actual = $user->toFeed();

        static::assertSame($expected, $actual, 'User::toFeed should return correct result');
    }

    private function makeUser(array $data): User
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setPassword($data['password']);
        $user->setRoles($data['roles']);

        return $user;
    }

    private function addLogin(User $user, array $data): User
    {
        $user->setLogin($data['login']);
        return $user;
    }

    private function setCreatedAtWithDelay(User $user, ?int $delay = null): User
    {
        if ($delay !== null) {
            \sleep($delay);
            $user->setCreatedAt();
        }

        return $user;
    }
}