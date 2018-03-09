<?php

/**
 * This is the model class for table "pegawai".
 *
 * The followings are the available columns in table 'pegawai':
 * @property string $id
 * @property string $nip
 * @property string $nama
 * @property string $alamat
 * @property string $tanggal_lahir
 * @property string $telpon
 * @property string $perusahaan
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 * @property PegawaiConfig[] $pegawaiConfigs
 * @property PegawaiCuti[] $pegawaiCutis
 */
class Pegawai extends CActiveRecord
{

    public $namaCabang;
    public $cabangTerakhirId;
    public $namaBagian;
    public $namaJabatan;
    public $namaNipPegawai;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pegawai';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nama, alamat, tanggal_lahir, telpon', 'required', 'message' => '[{attribute}] harus diisi!'],
            ['nip, nama, telpon, perusahaan', 'length', 'max' => 50],
            ['alamat', 'length', 'max' => 250],
            ['updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nip, nama, alamat, tanggal_lahir, telpon, perusahaan, updated_at, updated_by, created_at, namaNipPegawai, namaCabang, namaBagian, namaJabatan', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
            'pegawaiConfigs' => [self::HAS_MANY, 'PegawaiConfig', 'pegawai_id'],
            'pegawaiCutis' => [self::HAS_MANY, 'PegawaiCuti', 'pegawai_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nip' => 'NIP',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'tanggal_lahir' => 'Tgl Lahir',
            'telpon' => 'Telpon',
            'perusahaan' => 'Perusahaan',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaNipPegawai' => 'Nama / NIP'
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nip', $this->nip, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('alamat', $this->alamat, true);
        $criteria->compare("DATE_FORMAT(t.tanggal_lahir, '%d-%m-%Y')", $this->tanggal_lahir, true);
        $criteria->compare('telpon', $this->telpon, true);
        $criteria->compare('perusahaan', $this->perusahaan, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('CONCAT(nama, nip)', $this->namaNipPegawai, true);
        $criteria->compare('(SELECT cabang.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN cabang ON cabang.id = pegawai_mutasi.cabang_id WHERE pegawai_mutasi.pegawai_id = t.id)', $this->namaCabang);
        $criteria->compare('(SELECT bagian.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN bagian ON bagian.id = pegawai_mutasi.bagian_id WHERE pegawai_mutasi.pegawai_id = t.id)', $this->namaBagian);
        $criteria->compare('(SELECT jabatan.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN jabatan ON jabatan.id = pegawai_mutasi.jabatan_id WHERE pegawai_mutasi.pegawai_id = t.id)', $this->namaJabatan);

        $sort = [
            'attributes' => [
                'namaNipPegawai' => [
                    'asc' => 'CONCAT(nama, nip)',
                    'desc' => 'CONCAT(nama, nip) desc'
                ],
                'namaCabang' => [
                    'asc' => '(SELECT cabang.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN cabang ON cabang.id = pegawai_mutasi.cabang_id WHERE pegawai_mutasi.pegawai_id = t.id)',
                    'desc' => '(SELECT cabang.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN cabang ON cabang.id = pegawai_mutasi.cabang_id WHERE pegawai_mutasi.pegawai_id = t.id) desc',
                    'label' => 'Cabang'
                ],
                'namaBagian' => [
                    'asc' => '(SELECT bagian.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN bagian ON bagian.id = pegawai_mutasi.bagian_id WHERE pegawai_mutasi.pegawai_id = t.id)',
                    'desc' => '(SELECT bagian.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN bagian ON bagian.id = pegawai_mutasi.bagian_id WHERE pegawai_mutasi.pegawai_id = t.id) desc',
                    'label' => 'Bagian'
                ],
                'namaJabatan' => [
                    'asc' => '(SELECT jabatan.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN jabatan ON jabatan.id = pegawai_mutasi.jabatan_id WHERE pegawai_mutasi.pegawai_id = t.id)',
                    'desc' => '(SELECT jabatan.nama FROM pegawai_mutasi JOIN (SELECT pegawai_id, MAX(per_tanggal) per_tanggal FROM pegawai_mutasi GROUP BY pegawai_id) t_max ON t_max.pegawai_id = pegawai_mutasi.pegawai_id AND t_max.per_tanggal = pegawai_mutasi.per_tanggal JOIN jabatan ON jabatan.id = pegawai_mutasi.jabatan_id WHERE pegawai_mutasi.pegawai_id = t.id) desc',
                    'label' => 'Jabatan'
                ],
                '*'
            ]
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => $sort
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Pegawai the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        $this->tanggal_lahir = !empty($this->tanggal_lahir) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_lahir), 'Y-m-d') : NULL;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal_lahir = !is_null($this->tanggal_lahir) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_lahir), 'd-m-Y') : '0';
        return parent::afterFind();
    }

    // public static function getListPanjang()
    // {
    //     $sql = "
    //     SELECT 
    //         pegawai.id,
    //         pegawai.nama,
    //         pegawai.nip,
    //         cabang.nama namaCabang,
    //         bagian.nama namaBagian,
    //         jabatan.nama namaJabatan
    //     FROM
    //         pegawai_mutasi t
    //             JOIN
    //         (SELECT 
    //             pegawai_id, MAX(per_tanggal) per_tanggal_max
    //         FROM
    //             pegawai_mutasi
    //         GROUP BY pegawai_id) AS t_max ON t.pegawai_id = t_max.pegawai_id
    //             AND t.per_tanggal = t_max.per_tanggal_max
    //             JOIN
    //         pegawai ON t.pegawai_id = pegawai.id
    //             JOIN
    //         cabang ON t.cabang_id = cabang.id
    //             JOIN
    //         bagian ON t.bagian_id = bagian.id
    //             JOIN
    //         jabatan ON t.jabatan_id = jabatan.id
    //     ORDER BY pegawai.nama
    //    ";
    //     $model = Yii::app()->db->createCommand($sql)->queryAll();
    //     return CHtml::listData($model, 'id', function($model) {
    //                 return $model['nama'] . ' [' . $model['nip'] . ']' . ' [' . $model['namaCabang'] . '] [' . $model['namaBagian'] . '] [' . $model['namaJabatan'] . ']';
    //             });
    // }

    public static function getListPanjang()
    {
        $sql = "
        SELECT 
            pegawai.id,
            pegawai.nama,
            pegawai.nip,
            cabang.nama namaCabang,
            bagian.nama namaBagian,
            jabatan.nama namaJabatan
        FROM
            pegawai 
                LEFT JOIN
            pegawai_mutasi t ON t.pegawai_id = pegawai.id
                LEFT JOIN
            (SELECT 
                pegawai_id, MAX(per_tanggal) per_tanggal_max
            FROM
                pegawai_mutasi
            GROUP BY pegawai_id) AS t_max ON t.pegawai_id = t_max.pegawai_id
                AND t.per_tanggal = t_max.per_tanggal_max
                LEFT JOIN
            cabang ON t.cabang_id = cabang.id
                LEFT JOIN
            bagian ON t.bagian_id = bagian.id
                LEFT JOIN
            jabatan ON t.jabatan_id = jabatan.id
        ORDER BY pegawai.nama
       ";
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        return CHtml::listData($model, 'id', function($model) {
                    return $model['nama'] . ' [' . $model['nip'] . ']' . ' [' . $model['namaCabang'] . '] [' . $model['namaBagian'] . '] [' . $model['namaJabatan'] . ']';
                });
    }

    public function getNamaNipPegawai()
    {
        return $this->pegawai->nama . ' / ' . $this->pegawai->nip;
    }

    public function getCabangTerakhir()
    {
        $sql = "
        SELECT 
            cabang.nama
        FROM
            pegawai_mutasi
                NATURAL JOIN
            (SELECT 
                pegawai_id, MAX(per_tanggal) per_tanggal
            FROM
                pegawai_mutasi
            GROUP BY pegawai_id) t_max
                JOIN
            cabang ON cabang.id = pegawai_mutasi.cabang_id
        WHERE
            pegawai_id = :pegawaiId
               ";
        return Yii::app()->db->createCommand($sql)->bindValue(':pegawaiId', $this->id)->queryRow()['nama'];
    }

    public function getBagianTerakhir()
    {
        $sql = "
        SELECT 
            bagian.nama
        FROM
            pegawai_mutasi
                NATURAL JOIN
            (SELECT 
                pegawai_id, MAX(per_tanggal) per_tanggal
            FROM
                pegawai_mutasi
            GROUP BY pegawai_id) t_max
                JOIN
            bagian ON bagian.id = pegawai_mutasi.bagian_id
        WHERE
            pegawai_id = :pegawaiId
               ";
        return Yii::app()->db->createCommand($sql)->bindValue(':pegawaiId', $this->id)->queryRow()['nama'];
    }

    public function getJabatanTerakhir()
    {
        $sql = "
        SELECT 
            jabatan.nama
        FROM
            pegawai_mutasi
                NATURAL JOIN
            (SELECT 
                pegawai_id, MAX(per_tanggal) per_tanggal
            FROM
                pegawai_mutasi
            GROUP BY pegawai_id) t_max
                JOIN
            jabatan ON jabatan.id = pegawai_mutasi.jabatan_id
        WHERE
            pegawai_id = :pegawaiId
               ";
        return Yii::app()->db->createCommand($sql)->bindValue(':pegawaiId', $this->id)->queryRow()['nama'];
    }

}
