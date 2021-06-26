<?php


namespace App\Tests\unit\Service;


use App\DTO\UserDTO;
use App\Entity\Skill;
use App\Entity\User;
use App\Service\GroupService;
use App\Service\SkillService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Mockery\MockInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserSkillTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var EntityManagerInterface|MockInterface */
    private static $entityManager;
    private const CORRECT_USER = 1;
    private const INCORRECT_USER = 4;

    public static function setUpBeforeClass(): void
    {
        /** @var MockInterface|EntityRepository $repository */
        $repository = \Mockery::mock(EntityRepository::class);
        $repository->shouldReceive('find')->with(self::CORRECT_USER)->andReturn(new User());
        $repository->shouldReceive('find')->with(self::INCORRECT_USER)->andReturn(null);
        /** @var MockInterface|EntityManagerInterface $repository */
        self::$entityManager = Mockery::mock(EntityManagerInterface::class);
        self::$entityManager->shouldReceive('getRepository')->with(User::class)->andReturn($repository);
        self::$entityManager->shouldReceive('findOneBy')->with(User::class)->once()->andReturnSelf();
        self::$entityManager->shouldReceive('persist');
        self::$entityManager->shouldReceive('flush');
    }

    public function subscribeDataProvider(): array
    {
        return [
            'correct' => [self::CORRECT_USER, true],
            'author incorrect' => [self::INCORRECT_USER, false]
        ];
    }

    /**
     * @dataProvider subscribeDataProvider
     */
    public function testUserSkillReturnsCorrectResult(int $userId, bool $expected): void
    {
        $userService = new UserService(
            self::$entityManager,
            Mockery::mock(UserPasswordEncoderInterface::class),
            Mockery::mock(TagAwareCacheInterface::class),
            Mockery::mock(FormFactoryInterface::class),
            Mockery::mock(SkillService::class),
            Mockery::mock(GroupService::class)
        );

        $userRespository = self::$entityManager->getRepository(User::class);

        /** @var User $user */
        $user = $userRespository->findOneBy(['id' => $userId]);

        $userDTO = new UserDTO([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
            'newUserSkill' => ['новый тестовый навык'],
            'skillSelect' => [$user->getSkills()->getValues()[0]->getId()]
        ]);

        $actual = $userService->saveUser(new User(), $userDTO);

        static::assertSame($expected, $actual, 'Создание пользователя, возращен корректный результат');
    }

    public function testUserSkillReturnsAfterFirstError(): void
    {
        /** @var MockInterface|EntityRepository $repository */
        $repository = Mockery::mock(EntityRepository::class);
        $repository->shouldReceive('find')->with(self::INCORRECT_USER)->andReturn(null)->once();
        /** @var MockInterface|EntityManagerInterface $repository */
        self::$entityManager = Mockery::mock(EntityManagerInterface::class);
        self::$entityManager->shouldReceive('getRepository')->with(User::class)->andReturn($repository);
        self::$entityManager->shouldReceive('persist');
        self::$entityManager->shouldReceive('flush');

        $userService = new UserService(
            self::$entityManager,
            Mockery::mock(UserPasswordEncoderInterface::class),
            Mockery::mock(TagAwareCacheInterface::class),
            Mockery::mock(FormFactoryInterface::class),
            Mockery::mock(SkillService::class),
            Mockery::mock(GroupService::class)
        );

        $userRespository = self::$entityManager->getRepository(User::class);

        /** @var User $user */
        $user = $userRespository->findOneBy(['id' => self::INCORRECT_USER]);

        $userDTO = new UserDTO([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
            'newUserSkill' => ['новый тестовый навык'],
            'skillSelect' => [$user->getSkills()->getValues()[0]->getId()]
        ]);

        $actual = $userService->saveUser(new User(), $userDTO);
    }
}
