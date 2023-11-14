<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\Inventory;
use App\Entity\Message;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(MemberCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GuitarsVF');
    }

    public function configureMenuItems(): iterable
    {
        return
        [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Members', 'fa fa-users', Member::class),
            MenuItem::linkToCrud('Comments', 'fa fa-comments', Comment::class),
            MenuItem::linkToCrud('Galleries', 'fa fa-image', Gallery::class),
            MenuItem::linkToCrud('Guitars', 'fa fa-guitar', Guitar::class),
            MenuItem::linkToCrud('Inventories', 'fa fa-clipboard-list', Inventory::class),
            MenuItem::linkToCrud('Messages', 'fa fa-envelope', Message::class),
        ];
         
    }
}
