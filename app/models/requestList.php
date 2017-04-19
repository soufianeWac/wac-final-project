<?php 
namespace Silex\Form;

/*---------------------------------------------------------------------*/
/*             						//FUNCTION TOKEN INSERT//               		 */
/*---------------------------------------------------------------------*/
function userValidation($app, $token)
{
	$sqlRequest = 'INSERT INTO `tokens_account`(`tokename`)VALUES(?)'; 
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$token]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             					//FUNCTION VALID INSCRIPTION//               	 */
/*---------------------------------------------------------------------*/
function updateTokenValidation($app, $id, $token)
{
	$sqlRequest = 'UPDATE `token_validation` SET `user_id` = ? WHERE `tokename` = ?'; 
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$id, $token]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             					 	//FUNCTION USER SUBSCRIBE//               	 */
/*---------------------------------------------------------------------*/
function userSubscribe($app, $dataForm, $token_validation)
{
	$sqlRequest = 'INSERT INTO `users`(`civility`,`lastname`,`firstname`,`birthdate`,`mail`,`username`,`password`,`check_term`,`token_validation`)VALUES(?,?,?,?,?,?,?,?,?)'; 
	$resultReq = $app['db']->executeUpdate($sqlRequest, [
		$dataForm['civility'],
		$dataForm['lastname'],
		$dataForm['firstname'],
		$dataForm['birthdate'],
		$dataForm['mail'],
		$dataForm['username'],
		md5($dataForm['password'].SALT),
		$dataForm['checkTerms'],
		$token_validation
	]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION CREATE VIDEO//           				 */
/*---------------------------------------------------------------------*/
function videoSubscribe($app, $dataForm, $id)
{
	$sqlRequest = 'INSERT INTO `video_home`(`user_id`,`name`,`file`,`desc`,`image`,`state`)VALUES(?,?,?,?,?,?)';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [
		$id,
		$dataForm['name'],
		$dataForm['videoFile']->getClientOriginalName(),
		htmlentities(htmlspecialchars($dataForm['description'])."\n"),
		$dataForm['imageVideo'],
		$dataForm['display']
	]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION GET CATEGORY//               		 */
/*---------------------------------------------------------------------*/
function getCategory($app, $name){
	$getCategoryName = 'SELECT * FROM `category_video` WHERE `name` = ?';
	$resultCategory = $app['db']->fetchAssoc($getCategoryName, [$name]);
	return $resultCategory;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION GET CATEGORY//               		 */
/*---------------------------------------------------------------------*/
function getCategoryForVideos($app, $id){
	$getCategoryName = 'SELECT `category_video`.* FROM `category_video` INNER JOIN `video_home` ON category_video.id = video_home.id WHERE video_home.id = ?';
	$resultCategory = $app['db']->fetchAssoc($getCategoryName, [$id]);
	return $resultCategory;
}

/*---------------------------------------------------------------------*/
/*             //FUNCTION UPDATE CATEGORY ID FOR VIDEO//               */
/*---------------------------------------------------------------------*/
function videoCategory($app, $categoryId, $id)
{
	$sqlRequest = 'UPDATE `video_home` SET `category_id` = ? WHERE id = ?';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$categoryId, $id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             					 		//FUNCTION USER INFO//               	 	   */
/*---------------------------------------------------------------------*/
function userInfo($app, $id)
{
	$sqlRequest = 'SELECT * FROM users WHERE id = ?';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             				//FUNCTION USER VIDEO PROFIL//               	 	 */
/*---------------------------------------------------------------------*/
function userVideo($app, $id)
{
	$sqlRequest = 'SELECT * FROM video_home WHERE user_id = ?';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             			//FUNCTION GET VIDEO UNIQUE//               	 	   */
/*---------------------------------------------------------------------*/
function video($app, $id)
{
	$sqlRequest = 'SELECT * FROM video_home WHERE id = ?';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             				//FUNCTION GET LAST USER VIDEO//               	 */
/*---------------------------------------------------------------------*/
function lastUserVideo($app, $id)
{
	$sqlRequest = 'SELECT * FROM video_home WHERE user_id = ? ORDER BY created_at DESC LIMIT 1';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             					//FUNCTION CREATE COMMENT//               	 	 */
/*---------------------------------------------------------------------*/
function comSubscribe($app, $dataForm, $author, $videoId, $userId)
{
	$sqlRequest = 'INSERT INTO `commentaire_video`(`author`,`content`,`video_id`,`user_id`)VALUES(?,?,?,?)';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [
		$author,
		htmlentities(htmlspecialchars($dataForm['comment'])."\n"),
		$videoId,
		$userId
	]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             		//FUNCTION READ COMMENT WITH PAGINATE//              */
/*---------------------------------------------------------------------*/
function selectCom($app, $id, $page)
{
	$range = join(', ', [($page-1)*10, 1*10]);
	$sqlRequest = 'SELECT * FROM commentaire_video WHERE video_id = :id ORDER BY `created_at` DESC LIMIT '.$range;
	$row = $app['db']->prepare($sqlRequest);
  $row->bindValue('id', $id);
  $row->execute();
  $resultReq = $row->fetchAll(\PDO::FETCH_ASSOC);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             			//FUNCTION SELECT LAST COMMENT//               	 	 */
/*---------------------------------------------------------------------*/
function selectLastCom($app, $videoId, $id)
{
	$sqlRequest = 'SELECT * FROM commentaire_video WHERE video_id = ? AND id = ?';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$videoId, $id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION DELETE COMMENT//               	 */
/*---------------------------------------------------------------------*/
function deleteCom($app, $id, $userId)
{
	$sqlRequest = 'DELETE FROM `commentaire_video` WHERE id = ? AND user_id = ?';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$id, $userId]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION DELETE ACCOUNT//               	 */
/*---------------------------------------------------------------------*/
function deleteAccount($app, $id)
{
	$sqlRequest = 'DELETE FROM `commentaire_video` WHERE user_id = ?';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						//FUNCTION DELETE VIDEO//               	 */
/*---------------------------------------------------------------------*/
function deleteVideo($app, $id, $userId)
{
	$sqlRequest = 'DELETE FROM `video_home` WHERE id = ? AND user_id = ?';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$id, $userId]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						 	//FUNCTION EDIT ACCOUNT//               	 */
/*---------------------------------------------------------------------*/
function editAccount($app, $username, $avatar, $id)
{
	$sqlRequest = 'UPDATE `users` SET `username` = ?, `avatar` = ? WHERE id = ?';
	$resultReq = $app['db']->executeUpdate($sqlRequest, [$username, $avatar, $id]);
	return $resultReq;
}
/*---------------------------------------------------------------------*/
/*             						 	//FUNCTION COUNT COMMENT//               	 */
/*---------------------------------------------------------------------*/
function countCom($app, $id)
{
	$sqlRequest = 'SELECT COUNT(*) AS "count" FROM `commentaire_video` WHERE video_id = ?';
	$resultReq = $app['db']->fetchAssoc($sqlRequest, [$id]);
	return $resultReq;
}