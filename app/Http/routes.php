<?php
Route::get('/err', function () {
  trigger_error("エラーのテスト!", E_USER_ERROR);
});
