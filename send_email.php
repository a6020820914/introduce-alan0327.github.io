<?php
// send_email.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

// 解析 JSON 請求資料
$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$message = $data['message'] ?? '';

// 簡單的輸入驗證，檢查欄位是否填寫
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => '請填寫您的姓名。']);
    exit;
}

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => '請填寫您的電子郵件地址。']);
    exit;
}

if (empty($message)) {
    echo json_encode(['success' => false, 'message' => '請填寫您的訊息內容。']);
    exit;
}

// 檢查 email 格式是否正確
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => '請填寫有效的電子郵件地址。']);
    exit;
}

try {
    // 使用 PHPMailer 設定 SMTP 發送郵件
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8'; // 設定字符編碼為 UTF-8
    $mail->Host = 'smtp.gmail.com'; // 使用 Gmail SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'a6020820914@gmail.com'; // 您的 Gmail 帳號
    $mail->Password = 'myef kcph eyil qppk'; // 如果啟用兩步驟驗證，使用應用程式專用密碼
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 使用 TLS 加密
    $mail->Port = 587; // 設定端口

    // 設定郵件內容
    $mail->setFrom('a6020820914@gmail.com', 'Your Name');  // 發件人
    $mail->addAddress('a6020820914@gmail.com', 'Recipient Name');  // 收件人
    $mail->Subject = '新訊息來自您的個人網站';
    $mail->Body = "Name: $name\nEmail: $email\nMessage: $message";

    // 發送郵件
    if ($mail->send()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('郵件發送失敗');
    }
} catch (Exception $e) {
    // 記錄錯誤信息到日誌文件
    error_log("郵件發送失敗: Name: $name, Email: $email, Message: $message, Error: " . $e->getMessage(), 3, "/Users/luyicheng/Desktop/error.log");
    echo json_encode(['success' => false, 'message' => '郵件發送失敗，請稍後再試。']);
}
?>