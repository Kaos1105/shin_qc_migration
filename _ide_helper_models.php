<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $circle_id
 * @property int $activity_category
 * @property string|null $activity_title
 * @property string $date_intended
 * @property string $time_intended
 * @property string|null $date_execution
 * @property string|null $time_start
 * @property string|null $time_finish
 * @property float|null $time_span
 * @property int|null $participant_number
 * @property string|null $location
 * @property string|null $content1
 * @property string|null $content2
 * @property string|null $content3
 * @property string|null $content4
 * @property string|null $content5
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereActivityCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereActivityTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereContent1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereContent2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereContent3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereContent4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereContent5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereDateExecution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereDateIntended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereParticipantNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTimeFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTimeIntended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTimeSpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivity {}
}

namespace App\Models{
/**
 * App\Models\ActivityApproval
 *
 * @property int $id
 * @property int $promotion_circle_id
 * @property int $approver_classification
 * @property int|null $user_approved
 * @property string|null $date_approved
 * @property string|null $date_jan
 * @property int|null $user_jan
 * @property string|null $date_feb
 * @property int|null $user_feb
 * @property string|null $date_mar
 * @property int|null $user_mar
 * @property string|null $date_apr
 * @property int|null $user_apr
 * @property string|null $date_may
 * @property int|null $user_may
 * @property string|null $date_jun
 * @property int|null $user_jun
 * @property string|null $date_jul
 * @property int|null $user_jul
 * @property string|null $date_aug
 * @property int|null $user_aug
 * @property string|null $date_sep
 * @property int|null $user_sep
 * @property string|null $date_oct
 * @property int|null $user_oct
 * @property string|null $date_nov
 * @property int|null $user_nov
 * @property string|null $date_dec
 * @property int|null $user_dec
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereApproverClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateApr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateAug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateDec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateFeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateJan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateJul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateJun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateMar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateMay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateNov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateOct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereDateSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval wherePromotionCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserApr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserAug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserDec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserFeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserJan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserJul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserJun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserMar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserMay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserNov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserOct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApproval whereUserSep($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivityApproval {}
}

namespace App\Models{
/**
 * App\Models\ActivityApprovalsStatistics
 *
 * @property int $id
 * @property int $circle_id
 * @property int|null $year
 * @property int|null $kaizen_month_1
 * @property int|null $kaizen_month_2
 * @property int|null $kaizen_month_3
 * @property int|null $kaizen_month_4
 * @property int|null $kaizen_month_5
 * @property int|null $kaizen_month_6
 * @property int|null $kaizen_month_7
 * @property int|null $kaizen_month_8
 * @property int|null $kaizen_month_9
 * @property int|null $kaizen_month_10
 * @property int|null $kaizen_month_11
 * @property int|null $kaizen_month_12
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereKaizenMonth9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityApprovalsStatistics whereYear($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivityApprovalsStatistics {}
}

namespace App\Models{
/**
 * App\Models\ActivityAttachment
 *
 * @property int $id
 * @property int $activity_id
 * @property int $attachment_id
 * @property string|null $FileType
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereAttachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityAttachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivityAttachment {}
}

namespace App\Models{
/**
 * App\Models\ActivityOther
 *
 * @property int $id
 * @property int $activity_id
 * @property int $theme_id
 * @property string|null $content
 * @property float|null $time
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityOther whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivityOther {}
}

namespace App\Models{
/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property string|null $FileName
 * @property string|null $FilePath
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $FileNameOriginal
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileNameOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttachment {}
}

namespace App\Models{
/**
 * App\Models\Calendar
 *
 * @property int $id
 * @property string $dates
 * @property string $times
 * @property string $content
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperCalendar {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $category_name
 * @property int $is_display
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $deletable
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * App\Models\Circle
 *
 * @property int $id
 * @property string $circle_name
 * @property string $circle_code
 * @property string $date_register
 * @property int $place_id
 * @property int $user_id
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Circle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Circle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Circle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereCircleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereCircleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereDateRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereUseClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Circle whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCircle {}
}

namespace App\Models{
/**
 * App\Models\CircleLevel
 *
 * @property int $id
 * @property int $promotion_circle_id
 * @property int $member_id
 * @property int $axis_x_i
 * @property int $axis_x_ro
 * @property int $axis_x_ha
 * @property int $axis_x_ni
 * @property int $axis_x_ho
 * @property int $axis_y_i
 * @property int $axis_y_ro
 * @property int $axis_y_ha
 * @property int $axis_y_ni
 * @property int $axis_y_ho
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisXHa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisXHo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisXI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisXNi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisXRo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisYHa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisYHo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisYI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisYNi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereAxisYRo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel wherePromotionCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CircleLevel whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperCircleLevel {}
}

namespace App\Models{
/**
 * App\Models\Department
 *
 * @property int $id
 * @property string $department_name
 * @property int $bs_id
 * @property int $sw_id
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereBsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDepartmentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereSwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperDepartment {}
}

namespace App\Models{
/**
 * App\Models\EducationalMaterial
 *
 * @property int $id
 * @property string $title
 * @property string|null $educational_materials_type
 * @property string $file
 * @property string|null $date_start
 * @property string|null $date_end
 * @property int $display_order
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereEducationalMaterialsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationalMaterial whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperEducationalMaterial {}
}

namespace App\Models{
/**
 * App\Models\Homepage
 *
 * @property int $id
 * @property string $title
 * @property string|null $classification
 * @property string $url
 * @property string|null $date_start
 * @property string|null $date_end
 * @property int $is_display
 * @property int $display_order
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereIsDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homepage whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperHomepage {}
}

namespace App\Models{
/**
 * App\Models\Library
 *
 * @property int $id
 * @property string $title
 * @property string|null $library_type
 * @property string $file
 * @property string|null $date_start
 * @property string|null $date_end
 * @property int $display_order
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Library newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library query()
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereLibraryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperLibrary {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $user_id
 * @property int $circle_id
 * @property int $is_leader
 * @property string|null $classification
 * @property string|null $department
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereIsLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUseClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperMember {}
}

namespace App\Models{
/**
 * App\Models\NaviDetails
 *
 * @property int $id
 * @property int $history_id
 * @property int $qa_id
 * @property string $date_answer
 * @property int $answer_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereDateAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereQaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviDetails whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperNaviDetails {}
}

namespace App\Models{
/**
 * App\Models\NaviHistory
 *
 * @property int $id
 * @property string $date_start
 * @property int $user_id
 * @property int $story_id
 * @property int $starting_qa
 * @property int $thread_id
 * @property int $done_status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereDoneStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereStartingQa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NaviHistory whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperNaviHistory {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $notification_classify
 * @property string|null $message
 * @property string|null $date_start
 * @property string|null $date_end
 * @property int $display_order
 * @property int|null $use_classification
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotificationClassify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperNotification {}
}

namespace App\Models{
/**
 * App\Models\Place
 *
 * @property int $id
 * @property int $department_id
 * @property string $place_name
 * @property int $user_id
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Place newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Place newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Place query()
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place wherePlaceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereUseClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Place whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperPlace {}
}

namespace App\Models{
/**
 * App\Models\PlanByMonth
 *
 * @property int $id
 * @property int $plan_by_year_id
 * @property int $execution_order_no
 * @property string $contents
 * @property int $month_start
 * @property int $month_end
 * @property string|null $content_jan
 * @property string|null $content_feb
 * @property string|null $content_mar
 * @property string|null $content_apr
 * @property string|null $content_may
 * @property string|null $content_jun
 * @property string|null $content_jul
 * @property string|null $content_aug
 * @property string|null $content_sep
 * @property string|null $content_oct
 * @property string|null $content_nov
 * @property string|null $content_dec
 * @property string|null $in_charge
 * @property int $display_order
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentApr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentAug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentDec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentFeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentJan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentJul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentJun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentMar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentMay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentNov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentOct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContentSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereExecutionOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereInCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereMonthEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereMonthStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth wherePlanByYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByMonth whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperPlanByMonth {}
}

namespace App\Models{
/**
 * App\Models\PlanByYear
 *
 * @property int $id
 * @property int $year
 * @property string|null $vision
 * @property string|null $target
 * @property string|null $motto
 * @property string $prioritize_1
 * @property string|null $prioritize_2
 * @property string|null $prioritize_3
 * @property string|null $prioritize_4
 * @property string|null $prioritize_5
 * @property string|null $prioritize_6
 * @property string|null $prioritize_7
 * @property string|null $prioritize_8
 * @property string|null $prioritize_9
 * @property string|null $prioritize_10
 * @property int $meeting_times
 * @property int $meeting_hour
 * @property int $case_number_complete
 * @property int $case_number_improve
 * @property int $classes_organizing_objective
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereCaseNumberComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereCaseNumberImprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereClassesOrganizingObjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereMeetingHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereMeetingTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereMotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear wherePrioritize9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanByYear whereYear($value)
 * @mixin \Eloquent
 */
	class IdeHelperPlanByYear {}
}

namespace App\Models{
/**
 * App\Models\PromotionCircle
 *
 * @property int $id
 * @property int $circle_id
 * @property int $year
 * @property string|null $motto_of_the_workplace
 * @property string|null $motto_of_circle
 * @property float|null $axis_x
 * @property float|null $axis_y
 * @property int|null $target_number_of_meeting
 * @property float|null $target_hour_of_meeting
 * @property int|null $target_case_complete
 * @property int|null $improved_cases
 * @property int|null $objectives_of_organizing_classe
 * @property string|null $review_this_year
 * @property string|null $comment_promoter
 * @property int|null $display_order
 * @property int|null $statistic_classification
 * @property int|null $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle query()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereAxisX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereAxisY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereCommentPromoter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereImprovedCases($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereMottoOfCircle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereMottoOfTheWorkplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereObjectivesOfOrganizingClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereReviewThisYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereTargetCaseComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereTargetHourOfMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereTargetNumberOfMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereUseClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionCircle whereYear($value)
 * @mixin \Eloquent
 */
	class IdeHelperPromotionCircle {}
}

namespace App\Models{
/**
 * App\Models\PromotionTheme
 *
 * @property int $id
 * @property int $theme_id
 * @property int $progression_category
 * @property string $date_expected_start
 * @property string $date_expected_completion
 * @property string|null $date_actual_start
 * @property string|null $date_actual_completion
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme query()
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereDateActualCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereDateActualStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereDateExpectedCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereDateExpectedStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereProgressionCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionTheme whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperPromotionTheme {}
}

namespace App\Models{
/**
 * App\Models\Qa
 *
 * @property int $id
 * @property int $story_id
 * @property string $screen_id
 * @property string $title
 * @property string|null $comment
 * @property int|null $display_order
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Qa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereScreenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperQa {}
}

namespace App\Models{
/**
 * App\Models\QaAnswer
 *
 * @property int $id
 * @property int $qa_id
 * @property int $screen_classification
 * @property string $content
 * @property string|null $file_name
 * @property int|null $alignment
 * @property int|null $height
 * @property int|null $length
 * @property int|null $qa_linked
 * @property int $display_order
 * @property string|null $comment
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereAlignment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereQaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereQaLinked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereScreenClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaAnswer whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperQaAnswer {}
}

namespace App\Models{
/**
 * App\Models\QaQuestion
 *
 * @property int $id
 * @property int $qa_id
 * @property int $screen_classification
 * @property string $content
 * @property string $delta
 * @property string|null $file_name
 * @property int $file_size
 * @property int|null $alignment
 * @property int|null $height
 * @property int|null $length
 * @property string|null $comment
 * @property int $display_order
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereAlignment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereDelta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereQaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereScreenClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QaQuestion whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperQaQuestion {}
}

namespace App\Models{
/**
 * App\Models\Story
 *
 * @property int $id
 * @property string|null $story_classification
 * @property string $story_name
 * @property string|null $description
 * @property int|null $display_order
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Story newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story query()
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereStoryClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereStoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperStory {}
}

namespace App\Models{
/**
 * App\Models\Theme
 *
 * @property int $id
 * @property int $circle_id
 * @property string $theme_name
 * @property string $value_property
 * @property string $value_objective
 * @property string $date_start
 * @property string $date_expected_completion
 * @property string|null $date_actual_completion
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereDateActualCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereDateExpectedCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereValueObjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereValueProperty($value)
 * @mixin \Eloquent
 */
	class IdeHelperTheme {}
}

namespace App\Models{
/**
 * App\Models\Thread
 *
 * @property int $id
 * @property int $category_id
 * @property string $thread_name
 * @property int $is_display
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int|null $circle_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereCircleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereIsDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereThreadName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereUseClassification($value)
 * @mixin \Eloquent
 */
	class IdeHelperThread {}
}

namespace App\Models{
/**
 * App\Models\Topic
 *
 * @property int $id
 * @property int $thread_id
 * @property string|null $topic
 * @property string|null $file
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedBy($value)
 * @mixin \Eloquent
 */
	class IdeHelperTopic {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $user_code
 * @property string $name
 * @property int $role_indicator
 * @property string|null $position
 * @property string|null $email
 * @property string|null $phone
 * @property string $login_id
 * @property string $password
 * @property string $password_encrypt
 * @property string|null $remember_token
 * @property int $access_authority
 * @property int $display_order
 * @property int $statistic_classification
 * @property int $use_classification
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLoginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordEncrypt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleIndicator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatisticClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUseClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserCode($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

