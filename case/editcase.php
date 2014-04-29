<!--
	Dialog box for add/edit case.
	Requires common/common.css, case/editcase.css
-->

<table id="tbl_casedetails">
	<tr>
		<td><p>Category</p></td>
		<td><p><select>
			<option value="1">Crl. OP</option>
			<option value="2">RC</option>
			<option value="3">CA</option>
			<option value="4">WP</option>
			<option value="5">WA</option>
		</select></p></td>
	</tr>
	<tr>
		<td><p>Case Id</p></td>
		<td><p><input type="text"></input></p>
	</tr>
	<tr>
		<td><p>Investigated by</p></td>
		<td><p><input type="text" class="fullwidth"></input></p>
	</tr>
	<tr>
		<td><p>Petitioner</p></td>
		<td><p><textarea class="fullwidth"></textarea></p>
	</tr>
	<tr>
		<td><p>Respondent</p></td>
		<td><p><textarea class="fullwidth"></textarea></p>
	</tr>
	<tr>
		<td><p>Prayer</p></td>
		<td><p><textarea class="fullwidth"></textarea></p></td>
	</tr>
	<tr><td>&nbsp;</td><td><p><button>Save</button></p></td></tr>
</table>

