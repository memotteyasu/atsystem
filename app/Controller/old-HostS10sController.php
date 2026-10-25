<?php
App::uses('AppController', 'Controller');
/**
 * HostS10s Controller
 *
 * @property HostS10 HostS10s
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostS10sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Personalmessage','Gyoumumessage');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
    	break;
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
	    <?
	    i18n_http_input("SJIS");
	    i18n_http_output("SJIS");
	    /*
	     * 2017-10-04
	     * 支給回数月のセット方法を変更、下２桁だけにした。（前ゼロを足していたが・・）
	     */
	    /*
	     * 2016-01-07
	     * 所属変更
	     * 318,323,333,339,321,324,325,334 wo asahi
	     * 1327 wo meiji
	     * 347 wo add to asahi
	     */
	    /*
	     * 2015-12-03
	     * 218,128,136を商事から朝日に変更
	     */
	    /*
	     * 2015-10-06
	     * 加福（３４１)を商事から外し、朝日運輸所属に変更
	     * 三橋(1154)を明治に追加
	     */
	    /*
	     * 2015-07-03
	     * 342を商事に追加
	     */
	    /*
	     * 2015-01-06
	     * 34１を追加
	     */
	    /*
	     * 2015-01-01
	     * 337,340,1338を追加
	     */
	    /*
	     * 2014-10-31
	     * 00234河村を商事に追加
	     */
	    /*
	     * 2014-10-06
	     * 00336長内を商事に追加
	     */
	    /*
	     * 2014-10-03
	     * 00154三橋を商事に切り替え
	     */
	    /*
	     * 2014-08-25
	     * 00335を朝日商事で追加
	     */
	    /*
	     * 2014-07-19
	     * 00334を朝日商事で追加
	     */
	    /*
	     * 2014-07-11
	     * 00333を朝日商事で追加
	     */
	    /*
	     * 2014-07-02
	     * 00331朝日商事の人を削除
	     */
	    /* ========================================================= */
	    /*	system		:	朝日PCA給与
	     /*	subsystem	:
	     /*	auther		:	シーボックス
	     /*	c-date		:	2010/06/02
	     /*
	     /*	pgm-id		:	pca002
	     /*	pgm-name	:	月次インポートデータ作成（給与X版）
	     */
	    /* ========================================================= */
	    /*	parameter
	     $output_year  出力年
	     $output_month 出力月
	     /* ========================================================= */
	    switch($output_month){
	        case "12":
	            $output_month2 = "01";
	            break;
	        case "01":
	            $output_month2 = "02";
	            break;
	        case "02":
	            $output_month2 = "03";
	            break;
	        case "03":
	            $output_month2 = "04";
	            break;
	        case "04":
	            $output_month2 = "05";
	            break;
	        case "05":
	            $output_month2 = "06";
	            break;
	        case "06":
	            $output_month2 = "07";
	            break;
	        case "07":
	            $output_month2 = "08";
	            break;
	        case "08":
	            $output_month2 = "09";
	            break;
	        case "09":
	            $output_month2 = "10";
	            break;
	        case "10":
	            $output_month2 = "11";
	            break;
	        case "11":
	            $output_month2 = "12";
	            break;
	    }

	    $dummy0 = "00";
	    //2018-01-05
	    $output_month3 = $output_month2 * 1;
	    /* =========================================================/
	     前処理
	     ========================================================= */
	    //**データベース接続 */
	    $username = "cbox@CBOX";
	    $password = "CBOX";
	    $conn = ora_logon($username, $password);
	    if ($conn == false) {
	        print("データベースに接続できませんでした。");
	        exit;
	    }
	    /* =========================================================/
	     認証処理
	     ========================================================= */
	    //**ユーザ認証 */
	    $cur=ora_open($conn);
	    $sql = "SELECT *
	FROM 	asa_ユーザー
	WHERE 	(ユーザーＩＤ = '$c_id')";
	    ora_parse($cur,$sql);
	    ora_exec($cur);
	    ora_fetch ($cur);
	    if (ora_numrows($cur) == 0) {
	        ora_close($cur);
	        exit;
	    } else {
	        if (ora_getcolumn($cur, 3) != $c_pass) {
	            ora_close($cur);
	            exit;
	        }
	    }
	    /* =========================================================/
	     データ取得
	     ========================================================= */
	    // 月次データ取得
	    // ＊＊PCAと同期を取りやすくするため、csvのヘッダ名と一緒に登録＊＊//
	    // ＊＊修正するときは、項目名とセットで修正PCAの取り込み項目もチェック＊＊//
	    // ＊＊PCAの取り込みは、項目の順番が違うだけでPCA側の変更も必要なので注意！＊＊//
	    if($kai_cd=='asa'){
	        // 朝日運輸の場合（化学品を手伝っている人のダミー社員番号も除去
	        $syain_cd_sql = "
	and (社員ＣＤ<>'00002'
	and 社員ＣＤ<>'00005'
	and 社員ＣＤ<>'00203'
	and 社員ＣＤ<>'01327'
	and 社員ＣＤ<>'80293')";
	        $w_kai_cd=$kai_cd; // ワークに退避
	    }else if($kai_cd=='syo'){
	        // 朝日商事の場合、会社コードをasaに置き換え
	        $syain_cd_sql = "
	and ((社員ＣＤ='00234' and 会社ＣＤ='asa')
	or (社員ＣＤ='00326' and 会社ＣＤ='asa')
	or (社員ＣＤ='00331' and 会社ＣＤ='asa')
	or (社員ＣＤ='00335' and 会社ＣＤ='asa')
	or (社員ＣＤ='00336' and 会社ＣＤ='asa')
	or (社員ＣＤ='00337' and 会社ＣＤ='asa')
	or (社員ＣＤ='00340' and 会社ＣＤ='asa')
	or (社員ＣＤ='00342' and 会社ＣＤ='asa')
	or (社員ＣＤ='01338' and 会社ＣＤ='mei')
	or (社員ＣＤ='00276' and 会社ＣＤ='asa'))";
	        $w_kai_cd=$kai_cd; // ワークに退避
	        $kai_cd="asa";
	    }else if($kai_cd=='mei'){
	        // 明治運輸の場合
	        $syain_cd_sql = "
	and (社員ＣＤ='01026'
	or 社員ＣＤ='01030'
	or 社員ＣＤ='01327'
	or 社員ＣＤ='01032'
	or 社員ＣＤ='01350'
	or 社員ＣＤ='01352'
	or 社員ＣＤ='01353'
	or 社員ＣＤ='01346')";
	        $w_kai_cd=$kai_cd; // ワークに退避
	    }
	    $monthly_sql = "SELECT ";//sql
	    $monthly_pca[] = "社員コード";//PCAでの項目名
	    if($kai_cd == 'mei'){
	        $monthly_sql .= "
	    '1' || substr(社員ＣＤ,3),";
	    }else{
	        $monthly_sql .= "
	    substr(社員ＣＤ,-4),";
	    }
	    $monthly_pca[] = "支給回数月";
	    /* 2017-01-06
	     $monthly_sql .= "
	     substr(支給年月,-2),";
	     */
	    $monthly_sql .= "
    $output_month3,";
    $monthly_pca[] = "支給日";
    $monthly_sql .= "
    to_char(支給日,'YYYYMMDD'),";
    $monthly_pca[] = "要勤務日数";
    $monthly_sql .= "
    所定労働日数,";
    $monthly_pca[] = "出勤日数";
    $monthly_sql .= "
    出勤日数+教育日数+待機日数,";
    $monthly_pca[] = "事故欠勤日数"; //欠勤日数
    $monthly_sql .= "
    欠勤日数+休み日数,";
    $monthly_pca[] = "病気欠勤日数"; //休業日数
    $monthly_sql .= "
    給与支給項目２８,";
    $monthly_pca[] = "代休特休日数";
    $monthly_sql .= "
    代休日数+特休日数+指定休日数,";
    $monthly_pca[] = "休日出勤日数";
    $monthly_sql .= "
    休出日数,";
    $monthly_pca[] = "有給消化日数";
    $monthly_sql .= "
    有給日数,";
    $monthly_pca[] = "遅刻早退回数";
    $monthly_sql .= "
    遅刻早退回数,";
    $monthly_pca[] = "遅刻早退時間";
    $monthly_sql .= "
	trunc(遅刻早退時間,0) || ':' || DECODE( mod(遅刻早退時間,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "平日残業";
    $monthly_sql .= "
	trunc(残業時間１,0) || ':' || DECODE( mod(残業時間１,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "平日深夜";
    $monthly_sql .= "
	trunc(残業時間２,0) || ':' || DECODE( mod(残業時間２,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "休日残業";
    $monthly_sql .= "
	trunc(残業時間３,0) || ':' || DECODE( mod(残業時間３,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "休日深夜";
    $monthly_sql .= "
	trunc(出力残業時間１,0) || ':' || DECODE( mod(出力残業時間１,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "法定休日残業";
    $monthly_sql .= "
	trunc(出力残業時間２,0) || ':' || DECODE( mod(出力残業時間２,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "法定休日深夜";
    $monthly_sql .= "
	trunc(出力残業時間３,0) || ':' || DECODE( mod(出力残業時間３,1), 0.5, '30', 0, '00', NULL),";
    $monthly_pca[] = "手当７"; //電話手当（運輸）
    $monthly_sql .= "
    給与支給項目２２,";
    $monthly_pca[] = "手当８"; //走行歩合（運輸）
    $monthly_sql .= "
    給与支給項目１０,";
    $monthly_pca[] = "手当９"; //長距離（運輸）
    $monthly_sql .= "
    給与支給項目１２,";
    $monthly_pca[] = "手当１０"; //車両手当（運輸）
    $monthly_sql .= "
    給与支給項目１３,";
    $monthly_pca[] = "手当１１"; //乗務手当（運輸）
    $monthly_sql .= "
    給与支給項目１５,";
    $monthly_pca[] = "手当１２"; //トレーラ（運輸）
    $monthly_sql .= "
    給与支給項目１４,";
    $monthly_pca[] = "手当１３"; //チェーン（運輸）
    $monthly_sql .= "
    給与支給項目２４,";
    $monthly_pca[] = "手当１４"; //積載歩合（運輸）
    $monthly_sql .= "
    給与支給項目１１,";
    $monthly_pca[] = "手当１５"; //旅費
    $monthly_sql .= "
    給与支給項目１６,";
    $monthly_pca[] = "手当２３"; //その他
    $monthly_sql .= "
    給与支給項目１８,";
    $monthly_pca[] = "手当２４"; //休業手当
    $monthly_sql .= "
    給与支給項目３０,";
    $monthly_pca[] = "手当２５"; //休業控除
    $monthly_sql .= "
    給与支給項目２９,";
    $monthly_pca[] = "なし（未設定）";
    $monthly_sql .= "
    '',";
    $monthly_pca[] = "なし（未設定）";
    $monthly_sql .= "
    '',";
    $monthly_pca[] = "控除３"; //ガソリン
    $monthly_sql .= "
    給与控除項目１４,";
    $monthly_pca[] = "控除７"; //薬代
    $monthly_sql .= "
    給与控除項目１５,";
    $monthly_pca[] = "控除１０"; //弁当代
    $monthly_sql .= "
    給与控除項目１１,";
    $monthly_pca[] = "控除１２"; //燃料代
    $monthly_sql .= "
    0,";
    $monthly_pca[] = "控除１３"; //生保
    $monthly_sql .= "
    給与控除項目５+給与控除項目６,";
    $monthly_pca[] = "控除１４"; //見舞公傷
    $monthly_sql .= "
    給与控除項目１７+給与控除項目２１,";
    $monthly_pca[] = "控除１６"; //その他（その他、寮食事代、クリーニング代、作業服代）
    $monthly_sql .= "
    給与控除項目１８+給与控除項目１２+給与控除項目１３+
    給与控除項目１６,";
    $monthly_pca[] = "指定特休日数(給与回数５)";
    $monthly_sql .= "
    特休日数+指定休日数,";
    $monthly_pca[] = "総労働時間";
    $monthly_sql .= "
	DECODE(所属大分類,'01',
		trunc(総労働時間-残業時間１-残業時間２-残業時間３-出力残業時間１-出力残業時間２-出力残業時間３,0)
		|| ':' ||
		DECODE( mod(総労働時間-残業時間１-残業時間２-残業時間３-出力残業時間１-出力残業時間２-出力残業時間３,1), 0.5, '30', 0, '00', 0)
	,0),";
    $monthly_pca[] = "なし（未設定）";
    $monthly_sql .= "
    ''";
    if($w_kai_cd=='syo'){
        $monthly_sql .= "
	from CBOX.test_給与明細
	WHERE 支給年月 = ".$output_year.$output_month.$syain_cd_sql;
    }else{
        $monthly_sql .= "
	from CBOX.test_給与明細
	WHERE 会社ＣＤ='$kai_cd'
	  and 支給年月 = ".$output_year.$output_month.$syain_cd_sql;
    }
    //csvファイル作成
    $fp = fopen("monthly.csv","w");
    //csvの1行目カラム名
    $pca_col = "";
    $cnt =  count($monthly_pca);
    for($i=0;$i<$cnt;$i++){
        $pca_col .= '"'.$monthly_pca[$i] . '",';
    }
    fputs($fp,substr($pca_col,0,-1)."\r\n");
    $cur = ora_open($conn);
    ora_parse($cur,$monthly_sql);
    ora_exec($cur);
    while(ora_fetch($cur)){
        //データ1行ずつ
        $pca_col = "";
        for($i=0;$i<$cnt;$i++){
            $pca_col .= '"'. ora_getcolumn($cur, $i) . '",';
        }
        fputs($fp,substr($pca_col,0,-1)."\r\n");
    }
    ora_close($cur);
    fclose($fp);
    /* =========================================================/
     後処理
     ========================================================= */
    ora_logoff($conn);
    /* =========================================================/
     ＨＴＭＬの出力
     ========================================================= */
    //ini_set('zlib.output_compression', 'Off');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y h:i:s") . "GMT");
    header("Cache-Control: public, must-revalidate");
    header("Pragma: public");
    header('HTTP/1.1 200 OK');
    header('Status: 200 OK');
    header('Accept-Ranges: bytes');
    header("Content-Type: application/octet-stream; name=".$w_kai_cd.$output_year.$output_month."_".date('Ymd').".csv");
    header("Content-Disposition:attachment;filename=".$w_kai_cd.$output_year.$output_month."_".date('Ymd').".csv");
    readfile('monthly.csv');
    ?>

	}


}
