<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCard;

class VcardDownloadController extends Controller
{
    /**
     * Download vcard
     */
    public function __invoke(CommunityVolunteer $cmtyvol)
    {
        $this->authorize('view', $cmtyvol);

        // define vcard
        $vcard = new VCard();
        $vcard->addCompany(config('app.name'));

        if ($cmtyvol->family_name != null || $cmtyvol->first_name != null) {
            $vcard->addName($cmtyvol->family_name, $cmtyvol->first_name, '', '', '');
        }
        if ($cmtyvol->email != null) {
            $vcard->addEmail($cmtyvol->email);
        }
        if ($cmtyvol->local_phone != null) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $cmtyvol->local_phone), 'HOME');
        }
        if ($cmtyvol->whatsapp != null && $cmtyvol->local_phone != $cmtyvol->whatsapp) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $cmtyvol->whatsapp), 'WORK');
        }

        if (isset($cmtyvol->portrait_picture)) {
            $contents = Storage::get($cmtyvol->portrait_picture);
            if ($contents != null) {
                $vcard->addPhotoContent($contents);
            }
        }

        // return vcard as a download
        return $vcard->download();
    }
}
