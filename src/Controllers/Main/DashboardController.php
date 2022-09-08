<?php

namespace App\Controllers\Main;

class DashboardController extends BaseMainController
{
    public function index()
    {
        // abort_if(Gate::denies(['access_home']),401,'401 Unauthorized');
        return renderView('dashboard', ['page_title' => 'Beranda']);
    }
    public function profile()
    {
        // abort_if(Gate::denies(['access_home']),401,'401 Unauthorized');
        return renderView('profile', ['page_title' => 'Profile']);
    }

    public function updateProfile()
    {
        $data = $this->getDataRequest();
        if (isset($data['user_photo'])) {
            $img = $data['user_photo'];
            if ($img->isValid()) {
                $newName = $this->_auth->user->username . ".png";
                $img->move(APPPATH . '../storage/user_photo/', $newName, true);
            }
        }
        if(!empty($data['password'])){
            $this->_auth->user->password = $data['password'];
            $this->_auth->user->hashPassword();
            $this->_auth->user->update();
        }
        return redirect()->back();
    }

    public function pageNotFound()
    {
        abort_if(false, 404, '404 Not Found');
    }
}
