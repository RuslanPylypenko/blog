<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Services\FeedService;
use Illuminate\Support\Facades\Auth;

class FeedsController extends Controller
{

    /**
     * @var FeedService
     */
    private $feedService;

    /**
     * FeedsController constructor.
     * @param FeedService $feedService
     */
    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    public function index()
    {
        $cUser = Auth::guard()->user();
        return $this->feedService->getArticles($cUser);
    }

}