<!DOCTYPE html>
<html>
    <head>
        <title>Email Notification</title>
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
            rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
            rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
            rel="stylesheet">
        <style>
            body {
                margin: 0;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0); }
            table {
                caption-side: bottom;
                border-collapse: collapse; }
            .table > :not(caption) > * > * {
                padding: 0.5rem 0.5rem;
                background-color: var(--bs-table-bg);
                background-image: -webkit-gradient(linear, left top, left bottom, from(var(--bs-table-accent-bg)), to(var(--bs-table-accent-bg)));
                background-image: linear-gradient(var(--bs-table-accent-bg), var(--bs-table-accent-bg));
                border-bottom-width: 1px; }
            .table > tbody {
                vertical-align: inherit; }
            .table > thead {
                vertical-align: bottom; }
            .table > :not(:last-child) > :last-child > * {
                border-bottom-color: currentColor; }
            h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
                margin-top: 0;
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2; }
            h6, .h6 {
                font-size: 1rem; }
            p {
                margin-top: 0;
                margin-bottom: 1rem; }


        </style>
    </head>
    <body>
        <table style="width: 100%">
            <tbody>
            <tr>
                <td>
                    <table style="background-color: #f6f7fb; width: 100%">
                        <tbody>
                        <tr>
                            <td>
                                <table style="width: 650px; margin: 0 auto; margin-bottom: 30px">
                                    <tbody>
                                    <tr>
                                        <td style="color:#999"><span>Cost Estimate</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
                        <tbody>
                        <tr>
                            <td style="padding: 30px">
                                <h6 style="font-weight: 600">Approval Reviewer</h6>
                                <p>You have a cost estimate project that requires your review</p>
                                <p><span class="font-weight-bold"> Project Name</span> : {{$project->project_title ?? "-"}}</p>
                                <p class="font-weight-bold">Design Engineer</p>
                                <p>Electrical : {{$project->designEngineerElectrical?->profiles->full_name ?? "-"}}<br>Instrument : {{$project->designEngineerInstrument?->profiles->full_name ?? "-"}} <br> Mechanical : {{$project->designEngineerMechanical?->profiles->full_name ?? "-"}}<br>Civil : {{$project->designEngineerCivil?->profiles->full_name ?? "-"}}</p>
                                <p style="text-align: center"><a href="10.34.168.90:8080/project/{{$project->id}}" style="padding: 10px; background-color: #24695c; color: #fff; display: inline-block; border-radius: 4px;font-weight:600;text-decoration:none">Go to Cost Estimate Project</a></p>
                                <p style="margin-bottom: 0">
                                    Regards,<br>Cost Estimate Team</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="width: 650px; margin: 0 auto; margin-top: 30px">
                        <tbody>
                        <tr style="text-align: center">
                            <td>
                                <p style="color: #999; margin-bottom: 0">PTVI Engineering Project Services</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
