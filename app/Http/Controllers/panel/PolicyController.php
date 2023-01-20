<?php

namespace App\Http\Controllers\panel;

use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    //Policy List
    public function home()
    {
        $d['page_title'] = 'Policies';
        $d['policies'] = Policy::get();

        return view('panel.pages.policies', $d);
    }

    //Policy Detail
    public function detail($policy_guid)
    {
        $policy = Policy::where('policy_guid', $policy_guid)->first();
        $d['page_title'] = $policy->title_tr;
        $d['policy'] = $policy;

        return view('panel.pages.policy_detail', $d);
    }

    //Update Policy
    public function update(Request $r)
    {
        $policy = Policy::where('policy_guid', $r->policy_guid)->first();
        if(is_null($policy)) {
            return redirect()->back()->with('updateError', 'updateError');
        }

        $policy->text = $r->text;
        $policy->update();

        return redirect()->back()->with('updateSuccess', 'updateSuccess');
    }
}
