<?php

namespace Modules\Yunku\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 对input内容校验规则
     *
     * @var array
     */
    protected $rules = [
        'phone' => '',
        'deviceCode' => 'required|int',
        'siteId' => 'required|int',
        'port' => 'int',
        'alarmType' => 'required',
        'alarmDescribe' => 'required',
        'images' => 'array',
        'images.*' => 'url',



        'department_id'                => 'required|int|min:0',
        'catalog_id'                   => 'required|int|min:0',
        'category_id'                  => 'required|int|min:0',
        'summary'                      => 'required|string|max:200',
        'format_category'              => 'required|min:0|in_dict:dc_assets',
        'format_type'                  => 'required|string|max:16',
        'update_cycle'                 => 'required|min:0|in_dict:dc_assets',
        'educational_theme'            => 'required|min:0|in_dict:dc_assets',
        'educational_stage'            => 'required|min:0|in_dict:dc_assets',
        'source_system'                => 'required|min:0|in_dict:dc_assets',
        'share_type'                   => 'required|min:0|in_dict:dc_assets',
        'share_condition'              => 'string|max:200',
        'tags'                         => 'array',
        'attachments'                  => 'required_if:format_category,' . DcAsset::FORMAT_CATEGORY_FILE . '|array|size:1',
        'attachments.*.filename'       => 'required_if:format_category,' . DcAsset::FORMAT_CATEGORY_FILE . '|string|between:1,50',
        'attachments.*.hash'           => 'required_if:format_category,' . DcAsset::FORMAT_CATEGORY_FILE . '|string|size:32',
        'subject_table'                => 'required_if:format_category,' . DcAsset::FORMAT_CATEGORY_DATABASE . '|string|max:100',
        'asset_columns'                => 'required_if:format_category,' . DcAsset::FORMAT_CATEGORY_DATABASE . '|array|between:1,' . DcColumn::COLUMN_MAX_LIMIT,
        'asset_columns.*.column_name'  => 'required|distinct|string|max:50|alpha_underline',
        'asset_columns.*.chinese_name' => 'required|distinct|string|max:20',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notice()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
