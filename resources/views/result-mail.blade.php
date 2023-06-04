<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
</head>
<body>
    <p>
        <b>Hello {{$data['name'] }},</b> your Exam ({{$data['exam_name'] }}) review passed, now you can check your marks.
    </p>

    <a href="{{$data['url'] }}">Click here to go on results page</a>
    <p>Thank you for your time</p>

</body>
</html>