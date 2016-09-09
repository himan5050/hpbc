<?php
    $file = drupal_get_path('module', 'hpbc_cron_jobs') . '/tmp/interest_tmp.txt';
    if (!file_exists($file)) {
        echo '<font color="red"><b>You have already applied interest on all Live Loan accounts.</b></font>';
    }
    else {
        $handle = @fopen($file, "r"); //read line one by one
        $values = '';
        $error = 0;
        $counter = 1;

        while (!feof($handle)) {
            $buffer = fgets($handle, 4096); // Read a line.
            $values = explode("|", $buffer); //Separate string by the means of |

            if (isset($values[1]) && ($values[2] > 0)) {
                $sqld = "UPDATE `tbl_loan_detail` SET  o_principal='" . $values[5] . "',last_interest_calculated='" . $values[4] . "'  WHERE loan_id='" . $values[0] . "'";
                $sqld1 = "UPDATE `tbl_loan_interestld` SET  o_principle='" . $values[7] . "'  WHERE account_id='" . $values[1] . "' AND calculation_date='" . $values[3] . "'";
                $resd = db_query($sqld);
                $resd1 = db_query($sqld1);
                $sqli = "INSERT INTO `tbl_loan_interestld` (`id`, `account_id`, `type`, `amount`, `from_date`, `to_date`, `calculation_date`, `o_principle`, `reason`, `intbatch_id`) VALUES (NULL, '" . $values[1] . "', 'interest', '" . $values[2] . "', '" . $values[3] . "', '" . $values[4] . "', '" . $values[4] . "', '" . $values[6] . "', '212', '0')";
                $resi = db_query($sqli);
                $counter++;
                if (!($resi && $resd && $resd1)) {
                    $error = 1;
                }
            }
            $values = '';
        }
        if (!$error) {
            fclose($handle);
            unlink($file);
            echo '<font color="green"><b>Interest Applied Successfully.</b></font>';
        }
    }
?>