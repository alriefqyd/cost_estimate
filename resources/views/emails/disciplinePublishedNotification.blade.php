<!DOCTYPE html>
<html>
    <head>
        <title>Discipline Estimate Published</title>
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
                                    <span style="color: #00838f;">&#9993;</span> {{ $discipline }} Estimate Published
                                </h2>
                                <p style="margin: 0 0 15px 0;">Hello {{ $engineerName ?? 'Engineer' }},</p>

                                <p style="margin: 0 0 15px 0;">
                                    <strong>{{ $publishedByName }}</strong> just published the <strong>{{ $discipline }}</strong> cost estimate
                                    on a project you are also assigned to.
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
                                        <td style="padding: 10px 15px;"><strong>Published By</strong></td>
                                        <td style="padding: 10px 15px; color: #00838f; font-weight: 600;">{{ $publishedByName }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                @if(!empty($disciplineStatuses))
                                    @php
                                        $outstandingCount = collect($disciplineStatuses)->where('outstanding', true)->count();
                                        $publishedCount   = collect($disciplineStatuses)->where('isPublished', true)->count();
                                        $totalCount       = count($disciplineStatuses);
                                    @endphp

                                    <p style="margin: 20px 0 10px 0; font-weight: 600; font-size: 14px;">Discipline Status</p>
                                    <p style="margin: 0 0 12px 0; font-size: 13px; color: #555;">
                                        {{ $publishedCount }} of {{ $totalCount }} disciplines published.
                                        @if($outstandingCount > 0)
                                            <strong style="color: #c0392b;">{{ $outstandingCount }} still outstanding</strong> &mdash;
                                            the cost estimate can only be downloaded to PDF once every discipline is published and approved by its reviewer.
                                        @else
                                            All disciplines are published and approved &mdash; the cost estimate is ready to be downloaded to PDF.
                                        @endif
                                    </p>

                                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
                                        <thead>
                                        <tr style="background-color: #eef1f5;">
                                            <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Discipline</th>
                                            <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Estimate</th>
                                            <th style="padding: 8px 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Review</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($disciplineStatuses as $row)
                                            <tr>
                                                <td style="padding: 8px 12px; border-bottom: 1px solid #eee;">
                                                    {{ $row['label'] }}
                                                </td>
                                                <td style="padding: 8px 12px; border-bottom: 1px solid #eee; color: {{ $row['isPublished'] ? '#1e7e34' : '#c0392b' }}; font-weight: 600;">
                                                    {{ $row['designLabel'] }}
                                                </td>
                                                <td style="padding: 8px 12px; border-bottom: 1px solid #eee; color: {{ $row['outstanding'] ? '#c0392b' : '#1e7e34' }};">
                                                    {{ $row['reviewLabel'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

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
