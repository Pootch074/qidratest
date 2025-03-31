<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\QuestionnaireTree;
use Illuminate\Http\Request;

class QuestionnairesController extends Controller
{
    //
    public function index()
    {
        $questionnaires = QuestionnaireTree::all();
        return view('admin.questionnaires.index', compact('questionnaires'));
    }
}
