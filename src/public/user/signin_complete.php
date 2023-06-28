<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\InputPassword;
use App\UseCase\UseCaseInput\SignInInput;
use App\UseCase\UseCaseInteractor\SignInInteractor;

session_start();
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

try {
    if (empty($email) || empty($password)) {
        throw new Exception('パスワードとメールアドレスを入力してください');
    }

    $userEmail = new Email($email);
    $inputPassword = new InputPassword($password);
    $useCaseInput = new SignInInput($userEmail, $inputPassword);
    $useCase = new SignInInteractor($useCaseInput);
    $useCaseOutput = $useCase->handler();

    if (!$useCaseOutput->isSuccess()) {
        throw new Exception($useCaseOutput->message());
    }
    header('Location: ../index.php');
    exit();
} catch (Exception $e) {
    $_SESSION['errors'][] = $e->getMessage();
    header('Location: ./signin.php');
    exit();
}
