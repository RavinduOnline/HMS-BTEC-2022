           
         
           <div class="ward-bed-top-details">
                    <h3>Head Doctor Name - <?php echo "$HeadDoc" ?></h3>
                    <h3>Ward  - <?php echo "$wardNo" ?></h3>
                    <h3>Total Staff  -  <?php echo $nurses_count; ?></h3>
            </div>
                <hr/>

            <div class="ward-bed-container">
   
                <div class="viewpage-top-container">
                </div>

                <table class="detail-table">
                        <tr>
                            <th id="id-col">Staff No</th>
                            <th>Name</th>
                            <th>Position</th>
                        </tr>
                        
                        <?php 
                            if(!$nurse_list){
                                echo "<td colspan='3' style='text-align:center;'>
                                        <i class='fa-sharp fa-solid fa-hourglass'></i>  No Staff to Show
                                    </td>";
                            } else{
                                echo $nurse_list; 
                            }

                        ?>
                </table>
            
            </div>