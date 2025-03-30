<?php

function fileupload(Request $request, $diskName, $folderName): bool|string
{
    $name =  $request->file("file")->getClientOriginalName();
    $path = $request->file("file")->storeAs($folderName, $name, $diskName);
    return $path;

}
