<!DOCTYPE html>
<html>

<head>
    <title>Vazifa rad qilindi!</title>
</head>

<body>
    <h2>Assalamu Alaykum. Yuborgan ushbu vazifangiz rad qilindi!</h2>
    <h3>Rad etish sababi: <strong>{{ $taskMessage->title }}</strong></h3>
    <h3>Vazifa nomi: <strong>{{ $taskMessage->task_name }}</strong></h3>
    <h3>Vazifa haqida: <strong>{{ $taskMessage->description }}</strong></h3>
    <h3>Vazifa kategoriyasi: <strong>{{ $taskMessage->category_name }}</strong></h3>
    <h3>Vazifa tugash vaqti: <strong>{{ $taskMessage->original_task }}</strong></h3>
    <h3>Vazifa zarurlik darajasi: <strong>{{ $taskMessage->high }}</strong></h3>
</body>

</html>
