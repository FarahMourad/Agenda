<?php

namespace App\Http\Controllers;

use App\Models\Diary_pages;
use http\Env\Response;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
class DiaryController
{
    //ajax
    public function getLastPage(): JsonResponse
    {
        $current_user = auth()->user();
        $last_page = Diary_pages::where('user_id', $current_user->user_id)->latest('page_id')->first();
        $page_no = $last_page->page_id;
//        $page_no = $page_no * 2 - 1;
        return response()->json([
            "content" => $last_page->content,
            "page_no" => $page_no,
            "bookmarked" => $last_page->bookmarked
            ]);
    }

    public function retrieveBookmarked(): JsonResponse
    {
        $current_user = auth()->user();
        $page_no = [];
        $contents = [];
        $pages = Diary_pages::where([
            ['bookmarked', true],
            ['user_id', $current_user->user_id]
            ])->get();
        foreach ($pages as $page){
            $page_no1 = $page->page_id;
            $page_no1 = $page_no1 * 2 - 1;
            array_push($page_no, $page_no1);
            array_push($contents, $page->content);
        }
        return response()->json([
           "pages_no" => $page_no,
           "contents" => $contents
        ]);
    }

    public function searchForPage(Request $request): JsonResponse
    {
        $page_no = $request->page_no;
        //$page_no = ceil($page_no / 2);
        $current_user = auth()->user();
        $page = Diary_pages::where([
            ['page_id', $page_no],
            ['user_id', $current_user->user_id]
        ]);
        return response()->json(
            $page->content
        );
    }

//    public function deletePage(Request $request){ //delete from the middle
//        $page_no = $request->page_no;
//        $page_no = ceil($page_no / 2);
//        $current_user = auth()->user();
//        $page = Diary_pages::where([
//            ['page_id', $page_no],
//            ['user_id', $current_user->user_id]
//        ]);
//        $page->delete();
//    }

//    public function setContent(Request $request){
//        if ($request->pageContent == null)
//            return response()->noContent();
//        else{
//            $page_no = $request->page_no;
//            $page_no = ceil($page_no / 2);
//            $current_user = auth()->user();
//            $page = Diary_pages::where([
//                ['page_id', $page_no],
//                ['user_id', $current_user->user_id]
//            ])->first();
//            if ($page === null){
//                $page = new Diary_pages();
//                $page->page_id = $page_no;
//                $page->user_id = $current_user->user_id;
//            }
//            $page->content = $request->pageContent;
//            $page->bookmarked = $request->bookmarked;
//            $page->save();
////            return response()->noContent();
//        }
//    }

    public function setContent(Request $request){
        if ($request->pageContent == null)
            return response()->noContent();
        else{
            $page_no = $request->page_no;
//            $page_no = ceil($page_no / 2);
            $current_user = auth()->user();
            $page = Diary_pages::where([
                ['page_id', $page_no],
                ['user_id', $current_user->user_id]
            ])->first();
            if ($page === null){
                $page = new Diary_pages();
                $page->page_id = $page_no;
                $page->user_id = $current_user->user_id;

            }
            $page->content = $request->pageContent;
            $page->bookmarked = $request->bookmarked;
            $page->save();
            return response()->noContent();
        }
    }

    public function bookmarkPage(Request $request){
        $page_no = $request->page_no;
//        $page_no = ceil($page_no / 2);
        $current_user = auth()->user();
        $page = Diary_pages::where([
            ['bookmarked', true],
            ['user_id', $current_user->user_id]
        ])->first();
        if($page != null){
            $page->bookmarked = false;
            $page->save();
        }
        $page = Diary_pages::where([
            ['page_id', $page_no],
            ['user_id', $current_user->user_id]
        ]);
        $page->bookmarked = $request->bookmarked;
        $page->save();
    }

    public function deleteDiary() {
        $current_user = auth()->user();
        $pages = Diary_pages::where('user_id', $current_user->user_id);
        $pages->delete();
//        return response()->noContent();
    }
}
