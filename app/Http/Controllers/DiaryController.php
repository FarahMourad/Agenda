<?php

namespace App\Http\Controllers;

use App\Models\Diary_pages;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
                "left_page" => 1,
                "right_page" => 2,
                "left_bookmarked" => null,
                "right_bookmarked" => null
            ]);
        } else {

            $page_no = $last_page->page_id;
            if ($page_no % 2 == 0){
                return response()->json([
                    "left_content" => null,
                    "right_content" => null,
                    "left_page" => $page_no + 1,
                    "right_page" => $page_no + 2,
                    "left_bookmarked" => null,
                    "right_bookmarked" => null
                ]);
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
        $pages = Diary_pages::where([
            ['bookmarked', true],
            ['user_id', $current_user->user_id]
        ])->first();
        if ($pages == null){
            return response()->json([
                "left_page" => null,
                "left_content" => null,
                "right_page" => null,
                "right_content" => null,
                'left_bookmarked' => null,
                'right_bookmarked' => null
            ]);
        }
        $page_no = $pages->page_id;
        $content =  $pages->content;
        if ($page_no % 2 == 0){
            $pages = Diary_pages::where([
                ['page_id', $page_no - 1],
                ['user_id', $current_user->user_id]
            ])->first();
            $left_page = $page_no - 1;
            $left_content = $pages->content;
            $right_page = $page_no;
            $right_content = $content;
            $right_bookmarked = true;
            $left_bookmarked = false;
        } else{
            $pages = Diary_pages::where([
                ['page_id', $page_no + 1],
                ['user_id', $current_user->user_id]
            ])->first();
            $left_page = $page_no;
            $left_content = $content;
            if ($pages == null){
                $right_page = null;
                $right_content = null;
            } else{
                $right_page = $page_no + 1;
                $right_content = $pages->content;
            }
            $right_bookmarked = false;
            $left_bookmarked = true;
        }
        return response()->json([
            "left_page" => $left_page,
            "left_content" => $left_content,
            "right_page" => $right_page,
            "right_content" => $right_content,
            'left_bookmarked' => $left_bookmarked,
            'right_bookmarked' => $right_bookmarked
        ]);
    }

    public function searchForPage(Request $request): JsonResponse
    {
        $page_no = $request->page_no;
        $user_id = auth()->user()->user_id;
        $page = Diary_pages::where([
            ['page_id', $page_no],
            ['user_id', $user_id]
        ])->first();
        if ($page == null) {
            return response()->json([
                'left_page' => null,
                'right_page'  => null,
                'left_content' => null,
                'right_content'  => null,
                'left_bookmarked' => null,
                'right_bookmarked' => null
            ]);
        } else {
            if ($page_no % 2 == 0) {
                $left_page = Diary_pages::where([
                    ['page_id', $page_no - 1],
                    ['user_id', $user_id]
                ])->first();
                return response()->json([
                    'left_page' => $page_no - 1,
                    'right_page'  => $page_no,
                    'left_content' => $left_page->content,
                    'right_content'  => $page->content,
                    'left_bookmarked' => $left_page->bookmarked,
                    'right_bookmarked' => $page->bookmarked
                ]);
            } else {
                $right_page = Diary_pages::where([
                    ['page_id', $page_no + 1],
                    ['user_id', $user_id]
                ])->first();
                if ($right_page == null) {
                    $right_page_content = null;
                    $right_page_bookmarked = null;
                } else {
                    $right_page_content = $right_page->content;
                    $right_page_bookmarked = $right_page->bookmarked;
                }
                return response()->json([
                    'left_page' => $page_no,
                    'right_page'  => $page_no + 1,
                    'left_content' => $page->content,
                    'right_content' => $right_page_content,
                    'left_bookmarked' => $page->bookmarked,
                    'right_bookmarked' => $right_page_bookmarked
                ]);
            }
        }
    }

    public function setContent(Request $request): Response
    {
        $left_page_no = $request->left_page;
        $right_page_no = $request->right_page;

        $left_page_content = $request->left_page_content;
        $right_page_content = $request->right_page_content;
        if ($left_page_content != null || $right_page_content != null){
            if ($left_page_content != null){
                $current_user = auth()->user();
                $page = Diary_pages::where([
                    ['page_id', $left_page_no],
                    ['user_id', $current_user->user_id]
                ])->first();
                if ($page === null){
                    $page = new Diary_pages();
                    $page->page_id = $left_page_no;
                    $page->user_id = $current_user->user_id;
                }
                $page->content = $request->left_page_content;
                $page->save();
            }
            if ($right_page_content != null){
                $current_user = auth()->user();
                $page = Diary_pages::where([
                    ['page_id', $right_page_no],
                    ['user_id', $current_user->user_id]
                ])->first();
                if ($page === null){
                    $page = new Diary_pages();
                    $page->page_id = $right_page_no;
                    $page->user_id = $current_user->user_id;
                }
                $page->content = $right_page_content;
                $page->save();
            }
        }
        return response()->noContent();
    }

    public function bookmarkPage(Request $request)
    {
        $page_no = $request->page_no;
        $current_user = auth()->user();
        $old_page = Diary_pages::where([
            ['bookmarked', true],
            ['user_id', $current_user->user_id]
        ])->first();
        if($old_page != null){
            $old_page->bookmarked = false;
            $old_page->save();
        }
        $page = Diary_pages::where([
            ['page_id', $page_no],
            ['user_id', $current_user->user_id]
        ])->first();
        if ($page != null){
            $page->bookmarked = true;
            $page->save();
        }
    }

    public function deleteDiary()
    {
        $current_user = auth()->user();
        $pages = Diary_pages::where('user_id', $current_user->user_id);
        $pages->delete();
    }
}
