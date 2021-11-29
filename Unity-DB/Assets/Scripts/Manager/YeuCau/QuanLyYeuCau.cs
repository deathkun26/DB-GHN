using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class QuanLyYeuCau : ButtonHandler
{
    public static QuanLyYeuCau instance;
    public Transform container;
    public GameObject yeuCauPrefab;
    public Dropdown trangthai;
    //public InputField maVanDon;
    public InputField beginDay;
    public InputField endDay;

    [Space(10)]
    [Header("Chỉnh sửa")]
    public GameObject formChinhSua;
    public YeuCau yeuCauEdit;
    public InputField noidungEdit;
    public Text maYeuCau;


    private void Start()
    {
        if (instance == null)
            instance = this;
    }

    public override void PointerDownHandler()
    {
        Load();
    }

    public void Load()
    {
        Debug.Log("Loading");

        StartCoroutine(DanhSachYeuCau());
    }
    public void Xoa(YeuCau yeuCau)
    {
        StartCoroutine(XoaHandler(yeuCau.id.text));
        Destroy(yeuCau.gameObject);
        for (int i = 0; i < container.childCount; i++)
        {
            container.GetChild(i).gameObject.GetComponent<YeuCau>().stt.text = (i + 1).ToString();
        }
    }
    public void ChinhSua()
    {
        formChinhSua.SetActive(false);
        yeuCauEdit.noiDung.text = noidungEdit.text;
        StartCoroutine(ChinhSuaHandler());
    }
    IEnumerator XoaHandler(string id)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("delete", "");
        form.AddField("request_id", id);

        // * URL
        string url = "http://localhost/php/sp_request.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Xoá yêu cầu thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Xoá yêu cầu thất bại -> Error : " + result[0]);
            }
        }
    }

    IEnumerator ChinhSuaHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("update", "");
        form.AddField("request_id", yeuCauEdit.id.text);
        form.AddField("content", noidungEdit.text);

        // * URL
        string url = "http://localhost/php/sp_request.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Chỉnh sửa yêu cầu thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Chỉnh sửa yêu cầu thất bại -> Error : " + result[0]);
            }
        }
    }

    public IEnumerator DanhSachYeuCau()
    {
        Manager.ClearContainer(container, clearHeader: true);
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("filter_request", "");
        form.AddField("user_id", Manager.instance.userId);
        //form.AddField("order_id", maVanDon.text);
        form.AddField("status", (trangthai.value - 1).ToString());
        form.AddField("time_from", beginDay.text);
        form.AddField("time_to", endDay.text);

        Debug.Log(Manager.instance.userId);
        //Debug.Log(maVanDon.text);
        Debug.Log(trangthai.value - 1);
        Debug.Log(beginDay.text);
        Debug.Log(endDay.text);

        // * URL
        string url = "http://localhost/php/sp_request.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++) // ! Bỏ \n cuối
                {
                    //Debug.Log(result[i]);
                    string[] data = result[i].Split('\t');
                    GameObject yeuCauObj = Instantiate(yeuCauPrefab, container);
                    YeuCau yc = yeuCauObj.GetComponent<YeuCau>();
                    yc.stt.text = i.ToString();
                    yc.id.text = data[0];
                    if (data[1] == "0")
                    {
                        yc.trangthai.text = "Đã gửi";
                        yc.panelBtn.SetActive(true);
                        yc.trangthai.color = Color.blue;
                    }
                    else if (data[1] == "1")
                    {
                        yc.trangthai.text = "Đang xử lý";
                        yc.panelBtn.SetActive(false);
                        yc.trangthai.color = Color.yellow;
                    }
                    else if (data[1] == "2")
                    {
                        yc.trangthai.text = "Hoàn thành";
                        yc.panelBtn.SetActive(false);
                        yc.trangthai.color = Color.green;
                    }
                    else Debug.Log("Status code error");
                    yc.maVanDon.text = data[2];
                    yc.ngayTao.text = data[3];
                    yc.noiDung.text = data[4];
                }
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách yêu cầu -> Error : " + result[0]);
            }
        }
    }
}