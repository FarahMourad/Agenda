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
        if ($last_page == null){
            return response()->json([
                "left_content" => null,
                "right_content" => null,
                "left_page" => null,
                "right_page" => null,
                "left_bookmarked" => null,
                "right_bookmarked" => null
            ]);
        } else {
            $page_no = $last_page->page_id;
            if($page_no % 2 == 0){
                $left_content = Diary_pages::where([
                    ['user_id', $current_user->user_id],
                    ['page_id', $page_no - 1]
                ])->first();
                echo $last_page;
                $left_page = $page_no - 1;
                $right_page = $page_no;
                $left_bookmark = $left_content->bookmarked;
                $right_bookmark = $last_page->bookmarked;
                $left_content = $left_content->content;
                $right_content = $last_page->content;
            } else {
                $left_page = $page_no;
                $right_page = $page_no + 1;
                $left_bookmark = $last_page->bookmarked;
                $right_bookmark = null;
                $left_content = $last_page->content;
                $right_content = null;
            }
            return response()->json([
                "left_content" => $left_content,
                "right_content" => $right_content,
                "left_page" => $left_page,
                "right_page" => $right_page,
                "left_bookmarked" => $left_bookmark,
                "right_bookmarked" => $right_bookmark
            ]);
        }
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

    public function searchForPage(Request $request)
    {
        $page_no = $request->page_no;
        $user_id = auth()->user()->user_id;
        $page = Diary_pages::where([
            ['page_id', $page_no],
            ['user_id', $user_id]
        ]);
        if ($page == null) {
            return redirect()->back()->withErrors('msg', 'ERROR: empty diary');
        } else {
            if ($page_no % 2 == 0) {
                $left_page = Diary_pages::where([
                    ['page_id', $page_no - 1],
                    ['user_id', $user_id]
                ]);
                return response()->json([
                    'left_page' => $left_page->content,
                    'right_page'  => $page->content,
                    'left_bookmarked' => $left_page->bookmarked,
                    'right_bookmarked' => $page->bookmarked
                ]);
            } else {
                $right_page = Diary_pages::where([
                    ['page_id', $page_no + 1],
                    ['user_id', $user_id]
                ]);
                if ($right_page == null) {
                    $right_page_content = null;
                    $right_page_bookmarked = null;
                } else {
                    $right_page_content = $right_page->content;
                    $right_page_bookmarked = $right_page->bookmarked;
                }
                return response()->json([
                    'left_page' => $page->content,
                    'right_page' => $right_page_content,
                    'left_bookmarked' => $page->bookmarked,
                    'right_bookmarked' => $right_page_bookmarked
                ]);
            }
        }
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
