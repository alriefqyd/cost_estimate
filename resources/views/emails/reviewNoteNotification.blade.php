<!DOCTYPE html>
<html>
<head>
    <title>Review Note Notification</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
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
                            <h2 style="font-weight: 600; margin-bottom: 10px;">New Review Note Added</h2>
                            <p style="margin: 0 0 15px 0;">Hello {{ $engineerName }},</p>

                            <p style="margin: 0 0 15px 0;">
                                The <strong>{{ $discipline }}</strong> reviewer has added a note on the cost estimate project you are assigned to.
                            </p>

                            <p style="margin: 0 0 10px 0;"><strong>Project:</strong> {{ $project->project_title }}</p>
                            <p style="margin: 0 0 10px 0;"><strong>Reviewer:</strong> {{ $reviewerName }}</p>
                            <p style="margin: 0 0 10px 0;"><strong>Discipline:</strong> {{ $discipline }}</p>

                            <div style="margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #24695c; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; color: #555;"><strong>Note:</strong></p>
                                <p style="margin: 8px 0 0 0;">{{ $noteText }}</p>
                            </div>

                            <p style="text-align: center; margin: 25px 0;">
                                <a href="{{ url('project/' . $project->id) }}"
                                   style="padding: 12px 20px; background-color: #24695c; color: #fff; display: inline-block; border-radius: 4px; font-weight: 600; text-decoration: none;">
                                    View Project
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
