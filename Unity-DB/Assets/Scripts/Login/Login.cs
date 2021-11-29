using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
using UnityEngine.SceneManagement;
public class Login : MonoBehaviour
{
    public GameObject alert;
    public Text alertMessage;
    public InputField username;
    public InputField password;

    public void OnLogin()
    {
        if (ValidateInput())
            StartCoroutine(LoginHandler());
        else
            Alert("Vui lòng nhập tên tài khoản và mật khẩu");
    }
    public void OnSignUp()
    {
        SceneManager.LoadScene("Register");
    }
    void Alert(string message)
    {
        alert.SetActive(true);
        alertMessage.text = message;
    }
    public bool ValidateInput()
    {
        if (username.text == "")
            return false;
        if (password.text == "")
            return false;

        return true;
    }

    IEnumerator LoginHandler()
    {
        WWWForm form = new WWWForm();
        form.AddField("username", username.text);
        form.AddField("password", password.text);

        string url = "http://localhost/php/login.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                string[] data = result[1].Split('\t');
                PlayerPrefs.SetString("Id", data[0]);
                PlayerPrefs.SetString("Name", data[1]);
                if (data[2] == "0")
                    PlayerPrefs.SetString("Role", "Nhân viên");
                else
                    PlayerPrefs.SetString("Role", "Chủ cửa hàng");
                SceneManager.LoadScene("Manager");
            }
            else // * Request Fail
            {
                Alert("Đăng nhập không thành công -> Error : " + result[0]);
            }
        }
    }
}
