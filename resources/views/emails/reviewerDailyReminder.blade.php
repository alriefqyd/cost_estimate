<!DOCTYPE html>
<html>
    <head>
        <title>Daily Reminder – Projects Pending Your Review</title>
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
                                <h2 style="font-weight: 600; margin-bottom: 10px;">Daily Reminder – Pending Reviews</h2>
                                <p style="margin: 0 0 15px 0;">Hello {{ $reviewerName }},</p>

                                <p style="margin: 0 0 20px 0;">
                                    The following cost estimate project(s) are still awaiting your review and approval.
                                    Please take a moment to review them at your earliest convenience.
                                </p>

                                <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
                                    <thead>
                                    <tr style="background-color: #24695c; color: #fff;">
                                        <th style="padding: 10px 12px; text-align: left; border: 1px solid #1a4f45;">#</th>
                                        <th style="padding: 10px 12px; text-align: left; border: 1px solid #1a4f45;">Project Name</th>
                                        <th style="padding: 10px 12px; text-align: left; border: 1px solid #1a4f45;">Discipline(s) Pending</th>
                                        <th style="padding: 10px 12px; text-align: center; border: 1px solid #1a4f45;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pendingProjects as $index => $item)
                                    <tr style="background-color: {{ $index % 2 === 0 ? '#f9fafb' : '#fff' }};">
                                        <td style="padding: 10px 12px; border: 1px solid #dee2e6; vertical-align: top;">{{ $index + 1 }}</td>
                                        <td style="padding: 10px 12px; border: 1px solid #dee2e6; vertical-align: top; font-weight: 600;">
                                            {{ $item['project']->project_title ?? '-' }}
                                        </td>
                                        <td style="padding: 10px 12px; border: 1px solid #dee2e6; vertical-align: top;">
                                            {{ implode(', ', $item['disciplines']) }}
                                        </td>
                                        <td style="padding: 10px 12px; border: 1px solid #dee2e6; text-align: center; vertical-align: top;">
                                            <a href="{{ url('project/' . $item['project']->id) }}"
                                               style="padding: 6px 14px; background-color: #24695c; color: #fff; display: inline-block; border-radius: 4px; font-weight: 600; text-decoration: none; font-size: 12px;">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

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
