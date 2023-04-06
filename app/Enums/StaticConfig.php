<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/24/2019
 * Time: 2:51 PM
 */

namespace App\Enums;


class StaticConfig
{
    public static $Required = "入力必須項目です。";
    public static $Unique = "このデータは、すでに存在します。";
    public static $Number = "数値で入力してください。";
    public static $Min = "0以上の数値で入力してください。";
    public static $Max = "999999以下の数値で入力してください。";
    public static $Max_Length_5 = "このフィールドは5文字以下で設定して下さい。";
    public static $Max_Length_10 = "このフィールドは10文字以下で設定して下さい。";
    public static $Max_Length_20 = "このフィールドは20文字以下で設定して下さい。";
    public static $Max_Length_40 = "このフィールドは40文字以下で設定して下さい。";
    public static $Max_Length_100 = "このフィールドは100文字以下で設定して下さい。";
    public static $Max_Length_500 = "このフィールドは500文字以下で設定して下さい。";
    public static $DisplayOrder = "使用できる数字は「0-999999」です。";
    public static $Date = "日付が正しくありません。（YYYY/MM/DD）";
    public static $DateTime = "日付が正しくありません。（YYYY/MM/DD HH:mm）";
    public static $Time = "時間が正しくありません。（HH:mm）";
    public static $Time_Start = "開始時間が正しくありません。（HH:mm）";
    public static $Time_End = "終了時間が正しくありません。（HH:mm）";
    public static $Email = "メールアドレスが正しくありません。";
    public static $Url_Error = "URLが正しくありません。";
    public static $Set_All_Field = "「イ」「ロ」「ハ」「二」「ホ」全て選択してください。";
    public static $Non_Negative = "負にすることはできません";
    public static $Time_Range_Constrain = "開始時間は終了時間より短くなければなりません。";

    public static $Image_Upload = "ファイルjpeg、png、jpg、gif、svgのみ。";
    public static $Image_Size = "最大サイズ5MB。";
    public static $Library_Size = "最大サイズ10MB。";
    public static $File_Type = "ファイル pdf、xls、xlsx、doc、docx、ppt、pptx のみ。";
    public static $Image_Only = "画像のみ。";
    public static $File_Not_Allowed = "ファイルタイプは許可されていません。";
    public static $File_Size_10M = "このフィールドは10MB以下で設定して下さい。";
    public static $File_Size_5M = "このフィールドは5MB以下で設定して下さい。";

    public static $Delete_User = "このユーザーを削除しても、よろしいですか。";
    public static $Delete_Department = "この部門を削除しても、よろしいですか。";
    public static $Delete_Place = "この職場を削除しても、よろしいですか。";
    public static $Delete_Circle = "このサークルを削除しても、よろしいですか。";
    public static $Delete_Member = "このメンバーを削除しても、よろしいですか。";
    public static $Delete_PlanByYear = "この事務局年間計画を削除しても、よろしいですか。";
    public static $Delete_PlanByMonth = "この月間活動登録を削除しても、よろしいですか。";
    public static $Delete_Theme = "このテーマを削除しても、よろしいですか。";
    public static $Delete_Category = "このカテゴリを削除しても、よろしいですか。";
    public static $Delete_Thread = "このｽﾚｯﾄﾞを削除しても、よろしいですか。";
    public static $Delete_Topic = "このトピックを削除しても、よろしいですか。";
    public static $Delete_Promotion_Theme = "このテーマ進捗を削除しても、よろしいですか。";
    public static $Delete_Notification = "このお知らせを削除してもよろしいですか。";
    public static $Delete_Library = "この書庫を削除しますか。";
    public static $Delete_Homepage = "このリンクを削除しますか。";
    public static $Delete_Activity = "この活動記録を削除しても、よろしいですか。";
    public static $Delete_Calendar_Event = "この予定を削除しますか。";
    public static $Delete_Story = "このストーリーを削除しても、よろしいですか。";
    public static $Delete_QA = "このQA画面を削除しても、よろしいですか。";
    public static $Delete_Detail = "このナビ履歴明細を削除しても、よろしいですか。";

    public static $Cannot_Delete_User = "このユーザーは利用されているため、削除できません。";
    public static $Cannot_Delete_Circle_Member = "サークルのメンバーは削除できません。";
    public static $Cannot_Delete_Department = "この部門は利用されているため、削除できません。";
    public static $Cannot_Delete_Place = "この職場は利用されているため、削除できません。";
    public static $Cannot_Delete_Circle = "このサークルは利用されているため、削除できません。";
    public static $Cannot_Delete_Member = "このメンバーは利用されているため、削除できません。";
    public static $Cannot_Delete_Theme = "このテーマは利用されているため、削除できません。";
    public static $Cannot_Delete_Category = "このカテゴリは利用されているため、削除できません。";
    public static $Cannot_Delete_Thread = "このスレッドは利用されているため、削除できません。";
    public static $Cannot_Delete_Story = "このストーリーは利用されているため、削除できません。";

    public static $Image_Not_Found = "（画像が見つかりません）";
    public static $File_Not_Exist = "指定ファイルは存在していません。";
    public static $Promotion_Circle_Not_Exist = "このサークル推進は存在しません。";

    public static $Default_Display_Order = "999999";

    public static $Upload_Path_QaAnswer = '/storage/app/public/uploaded-files/qa-answer-upload/';
    public static $Upload_Path_QaQuestion = '/storage/app/public/uploaded-files/qa-question-upload/';

    public static $Path_Topic = '/storage/app/public/uploaded-files/topic-upload';
    public static $Path_Library = '/storage/app/public/uploaded-files/library-upload';
    public static $Path_EducationalMaterials = '/storage/app/public/uploaded-files/education-materials-upload';
    public static $Excel_Result = '/resources/template/exported';

    public static $View_Path_QaQuestion = '/storage/uploaded-files/qa-question-upload/';
    public static $View_Path_QaAnswer = '/storage/uploaded-files/qa-answer-upload/';
    
    public static $Upload_Path_ContentActivity = '/storage/app/public/uploaded-files/activity-content/';
    public static $Upload_Path_RequestToBossActivity = '/storage/app/public/uploaded-files/activity-RequestToBoss/';
    public static $Type_File_Content = 'Content';
    public static $Type_File_RequestToBoss = 'RequestToBoss';

    public static $QC_Category = 'ＱＣナビ履歴';

    public static $Now_Post = "（掲載中）";

    public static $Remove_Stamp = "このスタンプを削除してもよろしいですか？";
    public static $Cannot_Remove_Stamp = "あなたとあなたのサークルメンバーの切手しか削除できません。";

    public static $Empty_Delta = '{"ops":[{"insert":""}]}';

}
