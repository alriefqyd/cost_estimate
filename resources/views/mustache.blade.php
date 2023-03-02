<script id="js-template-work-element" type="x-tmpl-mustache">
     <tr class="js-work-element-input-column">
        <td>
            <input class="form-control typeahead form-control tt-input js-input-work-element-idx-@{{ no }}
                           js-work-element-input" type="text"
                           name="work_element[]"
                   placeholder="Work Element" autocomplete="off"
                   spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
        </td>
        <td class="text-center"><i class="fa fa-trash-o js-delete-work-element text-danger text-20" data-idx="@{{ no }}"></i></td>
    </tr>
</script>

<script id="js-template-work-item" type="x-tmpl-mustache">
    <tr class="js-work-item-input-column">
        <td class="text-center">@{{ workElementText }}</td>
        <td>@{{ workItemText }}</td>
        <td>@{{ vol }} @{{ unit }}</td>
        <td>
            <table class="table table-striped">
                <thead>
                <th>Title</th>
                <th>Unit</th>
                <th>Coef</th>
                <th>Rate (Rp)</th>
                <th>Amount (Rp)</th>
                </thead>
                <tbody>
                @{{ #manPowers }}
                <tr>
                    <td>@{{ manPowerTitle }}</td>
                    <td>@{{ unitPivot }}</td>
                    <td>@{{ laborCoefisient }}</td>
                    <td>@{{ rateHourly }}</td>
                    <td>@{{ amountPivot }}</td>
                </tr>
                @{{ /manPowers }}
                </tbody>
            </table>
        </td>
        <td>
            <table class="table table-striped">
                <thead>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price (Rp)</th>
                <th>Amount (Rp)</th>
                </thead>
                <tbody>
                @{{ #equipmentTools }}
                <tr>
                    <td>@{{ description }}</td>
                    <td>@{{ unit }}</td>
                    <td>@{{ quantity }}</td>
                    <td>@{{ unitPrice }}</td>
                    <td>@{{ amount }}</td>
                </tr>
                @{{ /equipmentTools }}
                </tbody>
            </table>
        </td>
        <td>
            <table class="table table-striped">
                <thead>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price (Rp)</th>
                <th>Amount (Rp)</th>
                </thead>
                <tbody>
                @{{ #materials }}
                <tr>
                    <td>@{{ description }}</td>
                    <td>@{{ unit }}</td>
                    <td>@{{ quantity }}</td>
                    <td>@{{ rate }}</td>
                    <td>@{{ amount }}</td>
                </tr>
                @{{ /materials }}
                </tbody>
            </table>
        </td>
         <td class="text-center"><i class="fa fa-trash-o js-delete-work-item text-danger text-20" data-idx="@{{ no }}"></i></td>
    </tr>
</script>
