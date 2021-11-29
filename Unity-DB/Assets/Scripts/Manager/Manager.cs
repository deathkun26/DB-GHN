using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.UI;

public static class JsonHelper
{
    public static T[] FromJson<T>(string json)
    {
        json = fixJson(json);
        Wrapper<T> wrapper = JsonUtility.FromJson<Wrapper<T>>(json);
        return wrapper.Items;
    }

    public static string ToJson<T>(T[] array)
    {
        Wrapper<T> wrapper = new Wrapper<T>();
        wrapper.Items = array;
        return JsonUtility.ToJson(wrapper);
    }

    public static string ToJson<T>(T[] array, bool prettyPrint)
    {
        Wrapper<T> wrapper = new Wrapper<T>();
        wrapper.Items = array;
        return JsonUtility.ToJson(wrapper, prettyPrint);
    }

    public static string fixJson(string value)
    {
        value = "{\"Items\":" + value + "}";
        return value;
    }

    [System.Serializable]
    private class Wrapper<T>
    {
        public T[] Items;
    }

}

[System.Serializable]
public class Tinh
{
    public string name;
    public int code;
    public string division_type;
    public string codename;
    public int phone_code;
    public int[] districts;
}


public class Manager : MonoBehaviour
{
    public Text usernameText;
    public Text roleText;
    public Text idText;
    public static Manager instance;
    public List<GameObject> buttonList;
    public Dropdown cuaHangDropdown;
    public string cuaHangCrt;
    public string role = "";
    public string username = "";
    public string userId;
    public Tinh[] danhSachTinh;
    public GameObject alert;
    public Text alertMessage;

    [Space(10)]
    [Header("Thay đổi thông tin")]
    public InputField tenNguoiDung;
    public InputField sdt;
    public InputField email;
    //public InputField cmnd;
    public GameObject formEdit;

    void Start()
    {
        if (instance == null)
            instance = this;

        userId = PlayerPrefs.GetString("Id");
        role = PlayerPrefs.GetString("Role");
        username = PlayerPrefs.GetString("Name");

        if (role != "Chủ cửa hàng")
        {
            buttonList[1].SetActive(false);
            buttonList[2].SetActive(false);
        }
        else
            Debug.Log("Chủ cửa hàng");

        usernameText.text = username;
        roleText.text = role;
        idText.text = "Id: " + userId;

        StartCoroutine(LayDanhSachTinh());

        StartCoroutine(LayDanhSachCuaHang());
        cuaHangCrt = cuaHangDropdown.captionText.text;
    }

    public void LoadFormChinhSua()
    {
        tenNguoiDung.text = "";
        sdt.text = "";
        email.text = "";
        //cmnd.text = "";
        formEdit.SetActive(true);
    }

    public void ChinhSua()
    {
        StartCoroutine(ChinhSuaHandler());
    }

    public void ResetActiveButton()
    {
        foreach (var button in buttonList)
        {
            var btn = button.GetComponent<ButtonHandler>();
            btn.active = false;
            btn.image.color = btn.exitColor;

            if (btn.panel != null)
                btn.panel.SetActive(false);
        }
    }

    // Todo: Thay đổi cửa hàng được lựa chọn từ dropdown
    public void SelectCuaHang()
    {
        cuaHangCrt = cuaHangDropdown.captionText.text;
        Debug.Log(cuaHangCrt);
        if (QuanLyDonHang.instance.panel.activeSelf)
        {
            QuanLyDonHang.instance.Load();
        }
    }

    public void CapNhatCuaHang()
    {
        StartCoroutine(LayDanhSachCuaHang());
    }

    IEnumerator ChinhSuaHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("update_user", "");
        form.AddField("user_id", userId);
        form.AddField("name", tenNguoiDung.text);
        form.AddField("phone", sdt.text);
        form.AddField("email", email.text);
        //form.AddField("cmnd", cmnd.text);


        // * URL
        string url = "http://localhost/php/user.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                username = tenNguoiDung.text;
                usernameText.text = tenNguoiDung.text;
                Debug.Log("Sửa đổi thông tin thành công");

            }
            else // * Request Fail
            {
                Debug.Log("Sửa đổi thông tin thất bại -> Error : " + result[0]);
            }
        }
    }

    IEnumerator LayDanhSachCuaHang()
    {
        cuaHangDropdown.ClearOptions();
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("owner_id", userId);
        form.AddField("store_list", "1");

        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++)
                {
                    // data = TenCH \t MaCH
                    string[] data = result[i].Split('\t');

                    cuaHangDropdown.options.Add(new Dropdown.OptionData(string.Join(" - ", data)));
                }
                if (cuaHangDropdown.options.Count != 0)
                {
                    cuaHangDropdown.value = 0;
                    cuaHangDropdown.captionText.text = cuaHangDropdown.options[0].text;
                    cuaHangCrt = cuaHangDropdown.captionText.text;
                }
                else
                    cuaHangCrt = "";
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách cửa hàng -> Error : " + result[0]);
            }
        }
    }

    IEnumerator LayDanhSachTinh()
    {
        // * URL
        string url = "https://provinces.open-api.vn/api/?depth=1";

        // * Request handler    
        using (UnityWebRequest www = UnityWebRequest.Get(url))
        {
            yield return www.SendWebRequest();

            string result = www.downloadHandler.text;

            //Debug.Log(result);

            danhSachTinh = JsonHelper.FromJson<Tinh>(result);

        }
    }

    public void Alert(string message)
    {
        alert.SetActive(true);
        alertMessage.text = message;
    }


    public static void ClearContainer(Transform container, bool clearHeader = true)
    {
        for (int i = container.childCount - 1; i >= (clearHeader ? 0 : 1); i--)
        {
            Destroy(container.GetChild(i).gameObject);
        }
    }

}

// * SendRequestHandler Template
// IEnumerator SendRequestHandler()
// {
//     // * Data field
//     WWWForm form = new WWWForm();
//     //form.AddField("username", nameField.text);
//     //form.AddField("password", passField.text);

//     // * URL
//     string url = "http://localhost/demotest/register.php";

//     // * Request handler
//     using (UnityWebRequest www = UnityWebRequest.Post(url, form))
//     {
//         yield return www.SendWebRequest();

//         string[] result = www.downloadHandler.text.Split(';');

//         // * Request Success
//         if (result[0] == "0")
//         {
//             for (int i = 1; i < result.Length; i++)
//             {
//                 // * Data Handler
//                 string[] data = result[i].Split('-');

//             }
//         }
//         else // * Request Fail
//         {
//             Debug.Log("Lấy danh sách cửa hàng -> Error : " + result[0]);
//         }
//     }
// }