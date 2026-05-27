<?php
namespace App\Controller;
use App\Exception\NotFoundException;
use App\Model\User;
use App\Service\Router;
use App\Service\Templating;

class UserController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $users = User::findAll();
        $html = $templating->render('user/index.html.php', [
            'users' => $users,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(?array $requestUser, Templating $templating, Router $router): ?string
    {
        if ($requestUser) {
            $user = User::fromArray($requestUser);
            // @todo missing validation
            $user->save();

            $path = $router->generatePath('user-index');
            $router->redirect($path);
            return null;
        } else {
            $user = new User();
        }

        $html = $templating->render('user/create.html.php', [
            'user' => $user,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $userId, ?array $requestUser, Templating $templating, Router $router): ?string
    {
        $user = User::find($userId);
        if (! $user) {
            throw new NotFoundException("Missing user with id $userId");
        }

        if ($requestUser) {
            $user->fill($requestUser);
            // @todo missing validation
            $user->save();

            $path = $router->generatePath('user-index');
            $router->redirect($path);
            return null;
        }

        $html = $templating->render('user/edit.html.php', [
            'user' => $user,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $userId, Templating $templating, Router $router): ?string
    {
        $user = User::find($userId);
        if (! $user) {
            throw new NotFoundException("Missing user with id $userId");
        }

        $html = $templating->render('user/show.html.php', [
            'user' => $user,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $userId, Router $router): ?string
    {
        $user = User::find($userId);
        if (! $user) {
            throw new NotFoundException("Missing user with id $userId");
        }

        $user->delete();
        $path = $router->generatePath('user-index');
        $router->redirect($path);
        return null;
    }
}
