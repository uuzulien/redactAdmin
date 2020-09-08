<?php


namespace App\Repositories\Upload;


use Illuminate\Support\Facades\Storage;

class ImageController
{
    /**
     * 验证文件是否合法
     */
    public function upload($file, $disk='public',$rootURL = 'http://image.5dan.com/') {
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['png', 'jpg', 'gif','JPG','PNG','GIF'])) {
            return false;
        }

        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            return false;
        }

        // 4.是否是通过http请求表单提交的文件
        if (! is_uploaded_file($tmpFile)) {
            return false;
        }

        // 5.每天一个文件夹,分开存储, 生成一个随机文件名

        $fileName = md5(time()) .mt_rand(0,9999).'.'. $fileExtension;

        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
            return ['img_href' => config('app.url') . '/storage/' . $fileName, 'img_sha1' => sha1_file($tmpFile)];
        }
    }
    /**
     * 验证文件是否合法
     */
    public function pyqUpload($file, $disk='public',$rootURL = 'http://image.5dan.com/') {
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['png', 'jpg','JPG','PNG'])) {
            return false;
        }

        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 800000) {
            return false;
        }

        list($width,$height) = getimagesize($tmpFile);
        if (!in_array($width.$height, ["800800","640800","800640"])){
            return false;
        }

        // 4.是否是通过http请求表单提交的文件
        if (! is_uploaded_file($tmpFile)) {
            return false;
        }

        // 5.每天一个文件夹,分开存储, 生成一个随机文件名

        $fileName = md5(time()) .mt_rand(0,9999).'.'. $fileExtension;

        $fileDir = date('Y_m_d').'/'.$fileName;

        if (Storage::disk($disk)->put($fileDir, file_get_contents($tmpFile)) ){
            $cmd_clear = "mv /www/wwwroot/WechatNovelAdmin/storage/app/public/{$fileDir} /www/wwwroot/image.5dan.com/";
            shell_exec($cmd_clear);
            return  ['img_href' => $rootURL . $fileName, 'img_sha1' => sha1_file($tmpFile)];
        }
    }
}