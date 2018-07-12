<div id="timeframe_panel">
   <form action="" method="POST" name="ClassScheduleForm" id="ClassScheduleForm">
      <table class="list view" width="50%">
         <thead>
            <tr>
               <th width="25%" align="center" style="text-align: center;">{$MOD.LBL_WEEKDAY}</th>
               <th width="55%" align="center" style="text-align: center;">{$MOD.LBL_TIMESLOT} <span class="required">*</span></th>
               <th width="20%" align="center" class="hour_item" style="text-align: center;">{$MOD.LBL_DURATION_HOUR} <span class="required">*</span></th>
            </tr>
         </thead>
         <tbody>
            <tr id="TS_Mon" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_MON}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Tue" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_TUE}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Wed" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_WED}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Thu" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_THU}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Fri" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_FRI}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Sat" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_SAT}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr id="TS_Sun" style="display: none;">
               <td nowrap align="left" style="text-align: center;">{$MOD.LBL_SUN}</td>
               <td nowrap align="center">
                  <table>
                     <tbody>
                        <tr>
                           <td>
                              <p class="timeOnly">
                                 <input class="time start" type="text" style="width: 70px; text-align: center;">
                                 - to -
                                 <input class="time end" type="text" style="width: 70px; text-align: center;">
                              </p>
                           </td>
                           <td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button addTimeSlot primary" value="Add" title="Add Time Slot"><img src="themes/default/images/id-ff-add.png" alt="Add Time Slot"></button></span></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td nowrap align="center" class="hour_item">
                  <table>
                     <tbody>
                        <tr>
                           <td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td>
                           <td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </form>
</div>