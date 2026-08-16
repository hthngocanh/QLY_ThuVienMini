<?php

function kiemTraDuocMuon($trangThai, $soSachDangMuon, $hanMucMuon)
{
    if ($trangThai !== "Hoạt động") {
        return false;
    }

    if ($soSachDangMuon >= $hanMucMuon) {
        return false;
    }

    return true;
}

function layLyDoKhongDuocMuon(
    $trangThai,
    $soSachDangMuon,
    $hanMucMuon
) {
    if ($trangThai !== "Hoạt động") {
        return "Tài khoản đang bị khóa";
    }

    if ($soSachDangMuon >= $hanMucMuon) {
        return "Đã đạt hạn mức mượn";
    }

    return "";
}
