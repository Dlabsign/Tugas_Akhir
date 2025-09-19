<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadExcelForm adalah model untuk form upload file excel.
 */
class UploadExcelForm extends Model
{
    public $excelFile;
    public $sesi_id;

    public function rules()
    {
        return [
            [['sesi_id'], 'integer'],
            // [['excelFile', 'sesi_id'], 'required', 'message' => 'Silakan pilih file.'],
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => false],
        ];
    }


    public function attributeLabels()
    {
        return [
            'excelFile' => 'File Excel Mahasiswa',
        ];
    }

    /**
     * Method untuk memvalidasi dan menyimpan file yang diunggah.
     * @return bool jika upload berhasil
     */
    public function upload()
    {
        if ($this->validate()) {
            // Buat direktori 'uploads' jika belum ada
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $filePath = 'uploads/' . $this->excelFile->baseName . '.' . $this->excelFile->extension;
            $this->excelFile->saveAs($filePath);
            return true;
        } else {
            return false;
        }
    }
}
