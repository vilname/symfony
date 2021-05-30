<?php


namespace App\Service;


use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthService
{
    private const TOKEN_EXPIRATION = '86400';

    private UserService $userService;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTEncoderInterface $jwtEncoder;
    private int $tokenTTL;
    private FormFactoryInterface $formFactory;

    public function __construct(UserService $userService,
                                UserPasswordEncoderInterface $passwordEncoder,
                                JWTEncoderInterface $jwtEncoder,
                                int $tokenTTL,
                                FormFactoryInterface $formFactory
    ) {
        $this->userService = $userService;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->tokenTTL = $tokenTTL;
        $this->formFactory = $formFactory;
    }

    public function isCredentialsValid(string $login, string $password): bool
    {
        $user = $this->userService->findUserByLogin($login);
        if ($user === null) {
            return false;
        }

        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function getToken(string $login): string
    {
        $user = $this->userService->findUserByLogin($login);
        $roles = $user ? $user->getRoles() : [];
        $tokenData = [
            'username' => $login,
            'roles' => $roles,
            'exp' => time() + $this->tokenTTL
        ];

        return $this->jwtEncoder->encode($tokenData);
    }

    public function getSaveForm(): FormInterface
    {
        return $this->formFactory->createBuilder(FormType::class, [])
            ->add('login', TextType::class)
            ->add('password', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
    }
}
