<!DOCTYPE html>
<html>
    <head>
        <title>Discipline Estimate Approved</title>
        <style>
            body {
                margin: 0;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
            }
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

                    <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px; font-family: Arial, sans-serif; border-collapse: collapse;">
                        <tbody>
                        <tr>
                            <td style="padding: 30px; color: #333; font-size: 14px;">
                                <h2 style="font-weight: 600; margin-bottom: 10px;">
                                    <span style="color: #24695c;">&#10004;</span> {{ $discipline }} Estimate Approved
                                </h2>
                                <p style="margin: 0 0 15px 0;">Hello {{ $engineerName ?? 'Engineer' }},</p>

                                <p style="margin: 0 0 15px 0;">
                                    Your <strong>{{ $discipline }}</strong> cost estimate has been
                                    <strong style="color: #24695c;">approved</strong> by the reviewer.
                                </p>

                                <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px; background-color: #f8f9fa; border-radius: 6px;">
                                    <tbody>
                                    <tr>
                                        <td style="padding: 10px 15px; border-bottom: 1px solid #dee2e6; width: 40%;"><strong>Project Name</strong></td>
                                        <td style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">{{ $project->project_title ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;"><strong>Discipline</strong></td>
                                        <td style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">{{ $discipline }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 15px;"><strong>Status</strong></td>
                                        <td style="padding: 10px 15px; color: #24695c; font-weight: 600;">Approved</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <p style="margin: 0 0 15px 0; font-size: 13px; color: #555;">
                                    You can view the project detail and monitor the overall approval status using the button below.
                                </p>

                                <p style="text-align: center; margin: 25px 0;">
                                    <a href="{{ url('project/' . $project->id) }}"
                                       style="padding: 12px 20px; background-color: #24695c; color: #fff; display: inline-block; border-radius: 4px; font-weight: 600; text-decoration: none;">
                                        View Cost Estimate Project
                                    </a>
                                </p>

                                <p style="margin-top: 20px; font-size: 13px; color: #666;">
                                    Regards,<br>
                                    <strong>Cost Estimate Team</strong>
                                </p>
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
