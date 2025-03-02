<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function updateInterests(Request $request, Profile $profile)
{
    $profile->interests()->sync($request->interests);
    return redirect()->back()->with('success', 'Intereses actualizados con éxito.');
}

}
