<?php
return [
    /*
     * メッセージモード
     * jp...日本語
     * en...英語
     */
    'log_language_mode' => 'en', // ログ出力言語
    'disp_language_mode' => 'jp', // 表示メッセージ言語
    /*
     * メッセージ一覧
     * jp...日本語
     * en...英語
     */
    'log' => [
        'en' => [
            'info' => [ // ログメッセージ
                'm1' => 'Start',
                'm2' => 'Get Db Data',
                'm3' => 'Return',
                'm4' => 'DB Transaction',
                'm5' => 'DB Insert Start',
                'm6' => 'DB Update Start',
                'm7' => 'DB Delete Start',
                'm8' => 'DB RollBack',
                'm9' => 'DB Commit',
                'm10' => 'Get Data',
                'm11' => 'Set Breadcrumbs Data',
                'm12' => 'Set Image Data',
                'm13' => 'Set Return Data',
                'm14' => 'Set Insert Data',
                'm15' => 'Set Update Data',
                'm16' => 'Set Delete Data',
                'm17' => 'Insert & Update Start',
                'm18' => 'Insert Start',
                'm19' => 'Update Start',
                'm20' => 'Delete Start',
                'm21' => 'Get GET Parameter',
                'm22' => 'Get POST Parameter',
                'm23' => 'Get PUT Parameter',
                'm24' => 'View Rendering',
                'm25' => 'Session Clear',
                'm26' => 'Get Session Data',
                'm27' => 'Get Form Data',
                'm28' => 'Set Session Data',
                'm29' => 'Redirect',
                'm30' => 'Insert Complited',
                'm31' => 'Update Complited',
                'm32' => 'Delete Complited',
                'm33' => 'Set Form Data',
                'm34' => 'Set Header Data',
                'm35' => 'Login OK',
                'm36' => 'Logout',
                'm37' => 'Send mail Start',
                'm38' => 'Password Reset Start',
                'm39' => 'Access Auth Check',
                'm40' => 'Get Master Data',
                'm41' => 'Get Data To Login Info',
                'm42' => 'Image Upload Start',
                'm43' => 'Convert Start',
                'm44' => 'Get Sub Data',
                'm45' => 'Get Blank No',
                'm46' => 'Set Upload Path',
                'm47' => 'Upload To Save',
                'm48' => 'Convert Seq',
                'm49' => 'File Exist Check',
                'm50' => 'Set Image Height(Auto Adjust)',
                'm51' => 'Set Image Height(Height Fixed)',
                'm52' => 'Converted To Save',
                'm53' => 'Convert Complite',
                'm54' => 'File Physics Delete',
                'm55' => 'Omit Two Step Verification',
                'm56' => 'Start Two Step Verification',
                'm57' => 'Complited Send Two Step Verification Code',
                'm58' => 'Two Step Verification OK',
                'm59' => 'Send Mail',
                'm60' => 'Validation Image Start'
            ],
            'debug' => [ // ログデバッグメッセージ
                'm1' => 'Argument Value',
                'm2' => 'Return Value',
                'm3' => 'GET Parameter Value',
                'm4' => 'View Value',
                'm5' => 'Form Value',
                'm6' => 'POST Parameter Value',
                'm7' => 'Set Old Session Value',
                'm8' => 'Set Session Value',
                'm9' => 'Get Image Data',
                'm10' => 'Send Mail Status',
                'm11' => 'Session Value',
                'm12' => 'Get Value',
                'm13' => 'Insert Value',
                'm14' => 'Update Value',
                'm15' => 'Upload Path',
                'm16' => 'Convert Pattern Value',
                'm17' => 'Convert Info Value',
                'm18' => 'Convert Fail Count value',
                'm19' => 'Convert Value',
                'm20' => 'Set Value',
                'm21' => 'Delete File',
                'm22' => 'Execution SQL'
            ],
            'error' => [ // ログエラーメッセージ
                'm1' => 'DB Insert Failed',
                'm2' => 'DB Update Failed',
                'm3' => 'DB Delete Failed',
                'm4' => 'Data Not Found',
                'm5' => 'Exception',
                'm6' => 'Session Not Found',
                'm7' => 'Insert Failed',
                'm8' => 'Update Failed',
                'm9' => 'Delete Failed',
                'm10' => 'Login Failed',
                'm11' => 'Send Mail Failed',
                'm12' => 'Upload Failed',
                'm13' => 'Convert Failed',
                'm14' => 'DB Insert Main Failed',
                'm15' => 'DB Insert Sub Failed',
                'm16' => 'DB Update Main Failed',
                'm17' => 'DB Update Sub Failed',
                'm18' => 'DB Delete Main Failed',
                'm19' => 'DB Delete Sub Failed',
                'm20' => 'File Not Found',
                'm21' => 'Two Step Verification Failed',
                'm22' => 'Parameter Failed',
                'm23' => 'Part Upload Failed'
            ],
            'alert' => [ // ログ警告メッセージ
                'm1' => 'Auth Error'
            ]
        ]
    ],
    'disp' => [
        'jp' => [
            'info' => [ // 表示メッセージ
                'm1' => '登録完了しました。',
                'm2' => '更新完了しました。',
                'm3' => '削除完了しました。',
                'm4' => '認証コードが再発行されました。メールが届くまでお待ち下さい。',
                'm5' => 'パスワードを更新しました。',
                'm6' => '画像がアップロードされました。'
            ],
            'error' => [ // 表示エラーメッセージ
                'm1' => '登録に失敗しました。',
                'm2' => '更新に失敗しました。',
                'm3' => '削除に失敗しました。',
                'm4' => 'ログインに失敗しました。',
                'm5' => '二段階認証の開始に失敗しました。',
                'm6' => '認証コードが無効、もしくは期限が切れています。再度入力するか再発行して下さい。',
                'm7' => '認証コード再発行に失敗しました。もう一度発行して下さい。',
                'm8' => 'メールを送信できませんでした。メールアドレスをご確認下さい。',
                'm9' => '既に使われています。違う番号を入力して下さい。',
                'm10' => 'ログインに失敗しました。再ログインして下さい。',
                'm11' => '選択ページへのアクセスが許可されていません。',
                'm12' => 'アップロードに失敗した画像があります。ご確認下さい。',
                'm13' => '障害が発生しました。管理者へお問い合わせ下さい。'
            ],
            'exception' => [ // 表示エラーメッセージ
                '401' => '401 Unauthorized',
                '404' => '404 Not Found',
                '500' => '500 Internal Server Error'
            ]
        ]
    ]
];
