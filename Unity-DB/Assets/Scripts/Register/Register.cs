using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
using UnityEngine.SceneManagement;

public class Register : MonoBehaviour
{
    public InputField tenTaiKhoan;
    public InputField tenNguoiDung;
    public InputField email;
    public InputField cmnd;
    public InputField sdt;
    public InputField matkhau;
    public InputField matkhauConfirm;
    public Dropdown role;
    public GameObject alert;
    public Text alertMessage;


    public void OnRegister()
    {
        if (ValidateInput())
        {
            if (matkhau.text != matkhauConfirm.text)
                Alert("Mật khẩu nhập lại không đúng");
            else
                StartCoroutine(RegisterHandler());
        }
    }

    public void OnLogin()
    {
        SceneManager.LoadScene("Login");
    }
    void Alert(string message)
    {
        alert.SetActive(true);
        alertMessage.text = message;
    }
    public bool ValidateInput()
    {
        if (tenTaiKhoan.text == "")
            return false;
        if (tenNguoiDung.text == "")
            return false;

        if (email.text == "")
            return false;
        if (sdt.text == "")
            return false;

        if (cmnd.text == "")
            return false;
        if (matkhau.text == "")
            return false;
        if (matkhauConfirm.text == "")
            return false;

        return true;
    }
    IEnumerator RegisterHandler()
    {
        WWWForm form = new WWWForm();
        form.AddField("username", tenTaiKhoan.text);
        form.AddField("password1", matkhau.text);
        form.AddField("name", tenNguoiDung.text);
        form.AddField("id_card_num", cmnd.text);
        form.AddField("email", email.text);
        form.AddField("phone", sdt.text);
        form.AddField("type", role.value);


        string url = "http://localhost/php/signup.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                string[] data = result[1].Split('\t');
                PlayerPrefs.SetString("Id", data[0]);
                PlayerPrefs.SetString("Role", role.captionText.text);
                PlayerPrefs.SetString("Name", tenNguoiDung.text);
                SceneManager.LoadScene("Manager");
            }
            else // * Request Fail
            {
                Alert("Đăng kí không thành công -> Error : " + result[0]);
            }
        }
    }
}
