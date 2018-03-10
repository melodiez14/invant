<?php

class LanguagesController extends BaseController {
    public function chooser(){
        $locale = Input::get('locale');
        Session::set('locale', $locale);
        Return Redirect::back();
    }
}