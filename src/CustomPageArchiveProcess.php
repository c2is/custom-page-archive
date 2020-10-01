<?php

  namespace CPA;

  use CPA\Controller\AcfController;
  use CPA\Controller\AdminPostListController;
  use CPA\Controller\AdminPostController;
  use CPA\Controller\MenuController;
  use CPA\Controller\PolylangController;
  use CPA\Controller\QueryController;
  use CPA\Controller\PostController;
  use CPA\Controller\RewriteRulesController;
  use CPA\Controller\YoastController;

  final class CustomPageArchiveProcess
{
    public function __construct()
    {
        /** Admin **/
        new MenuController();
        new AdminPostListController();
        new AdminPostController();
        new AcfController();

        /** Front **/
        new QueryController();
        new PostController();
        new RewriteRulesController();
        new YoastController();

        /** Languages **/
        new PolylangController();
    }
}
