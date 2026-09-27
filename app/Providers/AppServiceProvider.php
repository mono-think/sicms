<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // リポジトリ追加
        // 管理系
        $this->app->bind(\App\Repositories\Manage\PositionRepositoryInterface::class, \App\Repositories\Manage\PositionRepository::class);
        $this->app->bind(\App\Repositories\Manage\IndustryRepositoryInterface::class, \App\Repositories\Manage\IndustryRepository::class);
        $this->app->bind(\App\Repositories\Manage\BformatRepositoryInterface::class, \App\Repositories\Manage\BformatRepository::class);
        $this->app->bind(\App\Repositories\Manage\ProductsCategoryRepositoryInterface::class, \App\Repositories\Manage\ProductsCategoryRepository::class);
        $this->app->bind(\App\Repositories\Manage\PrefRepositoryInterface::class, \App\Repositories\Manage\PrefRepository::class);
        $this->app->bind(\App\Repositories\Manage\CompanyRepositoryInterface::class, \App\Repositories\Manage\CompanyRepository::class);
        $this->app->bind(\App\Repositories\Manage\CompanyBranchRepositoryInterface::class, \App\Repositories\Manage\CompanyBranchRepository::class);
        $this->app->bind(\App\Repositories\Manage\CompanyBranchNewsRepositoryInterface::class, \App\Repositories\Manage\CompanyBranchNewsRepository::class);
        $this->app->bind(\App\Repositories\Manage\ImageRepositoryInterface::class, \App\Repositories\Manage\ImageRepository::class);
        $this->app->bind(\App\Repositories\Manage\CompanyDepartmentRepositoryInterface::class, \App\Repositories\Manage\CompanyDepartmentRepository::class);
        $this->app->bind(\App\Repositories\Manage\AcntRepositoryInterface::class, \App\Repositories\Manage\AcntRepository::class);
        $this->app->bind(\App\Repositories\Manage\ContentRepositoryInterface::class, \App\Repositories\Manage\ContentRepository::class);
        $this->app->bind(\App\Repositories\Manage\EventRepositoryInterface::class, \App\Repositories\Manage\EventRepository::class);
        $this->app->bind(\App\Repositories\Manage\ProductsReviewRepositoryInterface::class, \App\Repositories\Manage\ProductsReviewRepository::class);
        $this->app->bind(\App\Repositories\Manage\ProductsDetailRepositoryInterface::class, \App\Repositories\Manage\ProductsDetailRepository::class);
        $this->app->bind(\App\Repositories\Manage\ProductsRepositoryInterface::class, \App\Repositories\Manage\ProductsRepository::class);
        $this->app->bind(\App\Repositories\Manage\MessageBordRepositoryInterface::class, \App\Repositories\Manage\MessageBordRepository::class);
        $this->app->bind(\App\Repositories\Manage\MessageReplyBordRepositoryInterface::class, \App\Repositories\Manage\MessageReplyBordRepository::class);
        $this->app->bind(\App\Repositories\Manage\VerificationRepositoryInterface::class, \App\Repositories\Manage\VerificationRepository::class);
        
        // フロント系
        $this->app->bind(\App\Repositories\PageViewRepositoryInterface::class, \App\Repositories\PageViewRepository::class);
        $this->app->bind(\App\Repositories\ProductsRepositoryInterface::class, \App\Repositories\ProductsRepository::class);
        $this->app->bind(\App\Repositories\ProductsDetailRepositoryInterface::class, \App\Repositories\ProductsDetailRepository::class);
        $this->app->bind(\App\Repositories\ProductsCategoryRepositoryInterface::class, \App\Repositories\ProductsCategoryRepository::class);
        $this->app->bind(\App\Repositories\CompanyRepositoryInterface::class, \App\Repositories\CompanyRepository::class);
        $this->app->bind(\App\Repositories\CompanyBranchFrontRepositoryInterface::class, \App\Repositories\CompanyBranchRepository::class);
        $this->app->bind(\App\Repositories\CompanyBranchNewsRepositoryInterface::class, \App\Repositories\CompanyBranchNewsRepository::class);
        $this->app->bind(\App\Repositories\EventRepositoryInterface::class, \App\Repositories\EventRepository::class);
        $this->app->bind(\App\Repositories\ContentRepositoryInterface::class, \App\Repositories\ContentRepository::class);
        $this->app->bind(\App\Repositories\MessageBordRepositoryInterface::class, \App\Repositories\MessageBordRepository::class);
        $this->app->bind(\App\Repositories\NewUpdateRepositoryInterface::class, \App\Repositories\NewUpdateRepository::class);
        
        // サービス追加
        // コンテンツ
        $this->app->bind('\App\Services\Manage');
        $this->app->bind('\App\Services');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ページネーション定義
        Paginator::useBootstrap();
    }
}
