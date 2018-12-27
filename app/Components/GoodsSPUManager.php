<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/23
 * Time: 9:19
 */

namespace App\Components;


use App\Models\GoodsSPU;
use Hamcrest\Core\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GoodsSPUManager extends Manager
{
    protected static $keys = [];

    protected static $primary_key = 'id';

    protected static $Modle = GoodsSPU::class;

    public static function getDetailsForApp(GoodsSPU $spu, $skuid = null)
    {
        $spu->detail;
        $spu->comment = $spu->comments()->orderBy('created_at', 'desc')->first();
        $spu->comment_count = $spu->comments()->count();
        $spu->haopinglv = "0";
        $comments_count = $spu->comments()->count();
        $comments_good_count = $spu->comments()->where('star', '>', 4)->count();
        if ($comments_count > 0) {
            $spu->haopinglv = ($comments_good_count / $comments_count * 100) . "%";
        }
//        $spu->is_favorite = $spu->favorites()->where('user_id', Auth::user()->id)->exists();
        $spu->thumb = getRealImageUrl($spu->thumb);
        $spec_matrix = array();
        $spec_ids = $spu->specs->pluck('id');
        foreach ($spec_ids as $spec_id) {
            foreach ($spec_ids as $other_spec_id) {

            }
        }

        foreach ($spu->skus as $sku) {
            $sku = GoodsSKUManager::getDetailsForApp($sku);
            if (!$skuid) {
                if (!$spu->main_sku or $spu->main_sku->price > $sku->price) {
                    $spu->main_sku = $sku;
                }
            } elseif ($sku->id == $skuid) {
                $spu->main_sku = $sku;
            }
        }
        return $spu;
    }
}