<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        section {
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    display:grid;
    margin: 50px auto;
    max-width: 750px;
}
section .company-name{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
hr{
    border: 1px solid rgb(20, 121, 184);;
    margin-top: 10px;
}
.month h3{
    padding: 5px;
    margin-top: 10px;
    font-weight: bold;
    font-size: 18px;
}
section .employee-summary{
    display: flex;
    padding: 5px;
    margin-top: 5px;
    justify-content: space-between;
    margin-bottom: 5px;
}
.employee-data .employee-data{
    display: flex;
    gap: 30px;
}
.employee-data div p{
    margin-top: 2px;
}
.salary-details{
    margin-top: 10px;
}
.salary-details table{
    border-collapse: collapse;
    width: 110%;
}
.salary-details table th{
    text-align:left;
    padding: 5px;
    color: rgb(20, 121, 184);
}
.salary-details table td{
    padding: 5px;
}
.salary-details table tbody{
    text-align: left;
}
.attendance-details{
    margin-top: 10px;
}
.attendance-details table{
    border-collapse: collapse;
    width: 131%;
}
.attendance-details table tbody{
    text-align: left;
}
.attendance-details table th{
    text-align:left;
    padding: 5px;
    color: rgb(20, 121, 184);
}
.attendance-details table td{
    padding: 5px;
}
.deduction-details{
    margin-top: 10px;
}
.deduction-details table{
    border-collapse: collapse;
    width: 94%;
}
.deduction-details table th{
    text-align:left;
    padding: 5px;
    color: rgb(20, 121, 184);
}
.deduction-details table td{
    padding: 5px;
}
.salary-details table tbody{
    text-align: left;
}
.totalnetpay{
    margin-top: 10px;
    text-align: center;
}
.totalnetpay h3{
    font-weight: 500;
    font-size: 17px;
}
.totalnetpay h3 span{
    font-weight: bold;
    font-size: 18px;
}
.note{
    text-align: center;
    margin-top: 10px;
   color: rgb(119 116 116);
   font-size: 14px;
}

.company-name .company-head h3{
    color: #2c3e50;
    font-size: 20px;
}
.company-name .company-head p{
    font-size: 15px;
    margin-top: 2px;
}
.company-logo img{
    width: 50px;
    height: 50px;
}
.employee h3{
    margin-top: 5px;
    color: rgb(20, 121, 184);
    padding: 5px;
    font-size: 18px;
}
.salary-details table .gross{
    font-weight: bold;
}
.deduction-details table .deduction{
    font-weight: bold;
}
.netpay{
    background-color:rgb(175, 213, 236);
    color: #121c27;
    width: 100%;
    font-weight: 600;

}
.employee-head p{
 color: rgb(119 116 116);
}
.employee-result p{
    color:  #2c3e50 ;
}
.employeenet-pay{
    margin-right: 50px;
}
.employeenet-pay h3{
    text-align: center;
    font-size: 13px;
    font-weight: 600;
}
.employeenet-pay p{
    text-align: center;
    font-size: 35px;
    font-weight: bold;
}
.employeenet-pay .paid{
    text-align: center;
    font-size: 12px;
     color: rgb(119 116 116);
}
    </style>
</head>
<body>
    <section>
        <div class="company-name">
            <div class="company-head">
                <h3>Mind2web</h3>
                <p>Mohali,India</p>
            </div>
            <div class="company-logo">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4ufOjMD7ZU4Ww2oHuSAuX1nTeLe6hZu_gIA&s">
            </div>
        </div>
        <hr>
        <div class="month">
            <h3>Payslip for the month of <span><?= date('F Y', strtotime($result['month']));?></span></h3>
        </div>
        <div class="employee">
            <h3>Employee Pay Summary</h3>
        </div>
        <div class="employee-summary">
            <div class="employee-data">
                <div class="employee-head">
                    <p>Payslip ID</p>
                    <P>Employee ID</P>
                    <p>Employee Name</p>
                    <p>Job Position</p>
                    <p>Date of Joining</p>
                    <p>Pay Month</p>
                    <p>Pay Date</p>

                </div>
                <div class="employee-result">
                    <p>: <?= $result['payslip_id'] ?></p>
                     <p>: <?= $result['id'] ?></p>
                    <p>: <?= $result['name'] ?></p>
                    <p>: <?= $result['position'] ?></p>
                    <p>: <?= $result['joining'] ?></p>
                    <p>: <?= date('F Y', strtotime($result['month']));?></p>
                    <p>: <?= date("d M Y", strtotime($pay_date)); ?></p>
                </div>

            </div>
            <div class="employeenet-pay">
                <h3>Employee Net Pay</h3>
                <p>₹<?= number_format($result['net_salary'],2) ?></p>
                <p class="paid">Paid Days : <?= $result['present_days'] ?> | LOP Days : <?= $result['absent_days'] ?></p>
            </div>
        </div>
        <hr>

        <div class="salary-details">
            <table>
                <thead>
                    <tr>
                        <th>Earnings</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td>₹<?= number_format($result['basic_salary'],2) ?></td>
                    </tr>
                    <tr>
                        <td>House Rent Allowance</td>
                        <td>₹<?= number_format($result['hra'],2) ?></td>
                    </tr>
                    <tr class="gross">
                        <td>Gross Earnings</td>
                        <td>₹<?= number_format($result['gross_salary'],2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>

        <div class="attendance-details">
            <table>
                <thead>
                    <tr>
                        <th>Attendence</th>
                        <th>Total Days</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Present Days</td>
                        <td><?= $result['present_days'] ?></td>
                    </tr>
                    <tr>
                        <td>Absent Days</td>
                        <td><?= $result['absent_days'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>

        <div class="deduction-details">
            <table>
                <thead>
                    <tr>
                        <th>Deductions</th>
                        <th>(-)Amounts</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Professional Tax</td>
                        <td>₹<?= number_format($result['deduction'],2) ?></td>
                    </tr>
                    <tr>
                        <td>Absent Days</td>
                        <td>₹<?= number_format($absentdeduction,2) ?></td>
                    </tr>
                    <tr class="deduction">
                        <td>Total Deductions</td>
                        <td>₹<?= number_format($result['deductions'],2) ?></td>
                    </tr>
                    <tr class="netpay">
                        <td>Net Pay (Gross Earnings - Deductions)</td>
                        <td>₹<?= number_format($result['net_salary'],2) ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="totalnetpay">
            <h3>Total Net Payable <span>₹<?= number_format($result['net_salary'],2) ?></span></h3>
        </div>
        <hr>
        <div class="note">
            <p>--This Document is generated by Mind2Web Payroll, therefore ,a signature is not required--</p>
        </div>
    </section>
</body>
</html>